<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Articulo;
use App\Models\Paciente;
use App\Models\CajaChica;
use App\Models\Comprobante;
use App\Services\PDFService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\HistoriaClinica;
use Illuminate\Validation\Rule;
use App\Models\DetalleComprobante;
use Illuminate\Support\Facades\DB;
use App\Services\ComprobanteService;
use App\Http\Resources\ComprobanteResource;

class ComprobanteController extends Controller
{
    use ApiResponser;
    protected $comprobanteService;
    public function __construct(ComprobanteService $comprobanteSrv)
    {
        $this->comprobanteService = $comprobanteSrv;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comprobantes = Comprobante::with(['persona', 'sede', 'detalles.articulo'])
        //->orderBy('fecha_emision', 'desc')
        ->get();

        return ComprobanteResource::collection($comprobantes)
        ->response()
        ->setStatusCode(200);
    }

    public function count(Request $request)
    {
      $sede = $request->query("id_sede");
      if(isset($sede))
      {
          $counted = Comprobante::where("id_sede",$sede)->get()->count();
          return $this->successResponse($counted);
      }
      else
        return $this->successResponse(Comprobante::all()->count());
    }

    public function getLastItem()
    {
        $comp = Comprobante::all()->sortByDesc(function($item){
            return (int) $item->numero;
        })->first();
        return $this->successResponse($comp);
    }

    public function searchComprobantes(Request $request)
    {
        // Obtener los parámetros de búsqueda
        $serie = $request->get('serie', null);
        $numero = $request->get('numero', null);
        
        //$fechaInicio = $request->get('fecha_inicio', null);
        //$fechaFin = $request->get('fecha_fin', null);

        // Validar que al menos uno de los parámetros esté presente
        // if (is_null($numero) && is_null($serie) && is_null($fechaInicio) && is_null($fechaFin)) {
        if (is_null($numero) && is_null($serie)) {    
            return response()->json([], 200); // Si todos los parámetros están vacíos, devolver lista vacía
        }
    
        // Crear la consulta base
        $query = Comprobante::with(['persona', 'sede', 'detalles.articulo']);
    
        // Filtrar por número de comprobante si se proporciona
        if ($numero) {
            $query->whereRaw('LOWER(numero) LIKE ?', ['%' . strtolower($numero) . '%']);
        }
    
        // Filtrar por serie si se proporciona
        if ($serie) {
            $query->whereRaw('LOWER(serie) LIKE ?', ['%' . strtolower($serie) . '%']);
        }
    
        // Filtrar por fecha de emisión si se proporcionan las fechas de inicio y fin
        /*if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha_emision', [
                \Carbon\Carbon::parse($fechaInicio)->startOfDay(),
                \Carbon\Carbon::parse($fechaFin)->endOfDay()
            ]);
        }*/
    
        // Ejecutar la consulta y obtener los resultados
        $comprobantes = $query->orderBy('fecha_emision', 'desc')->get();
    
        // Retornar los resultados con el recurso
        return ComprobanteResource::collection($comprobantes)
            ->response()
            ->setStatusCode(200);
    }

    public function verificationPersonDebt(Persona $persona)
    {
        try
        {
            \Log::info("CHECK PERSONA",["persona"=>$persona]);
            $idPersona = $persona['id'];
            \Log::info("ID PERSONA",["id" => $idPersona]);
            $paciente = Paciente::where(['id_persona'=>$idPersona])->first();
            \Log::info("CHECK PACIENTE",["paciente"=>$paciente]);
            if(!$paciente) return $this->errorResponse("No se ha encontrado a la persona",404);

            $deuda = HistoriaClinica::with("comprobante:id,deuda")->where([
                'id_paciente' => $paciente->id,
                'estado_pago' => 2
            ])
            ->get()
            ->unique("uuid")
            ->map( function ($item) {
                return [
                    'id_articulo' => $item->id_articulo,
                    'deuda' => $item->comprobante?->deuda ?? 0
                ];
            })
            ->values();

            return $this->successResponse($deuda);
        }
        catch(\Exception $e)
        {
            \Log::error("ALGO SALIO MAL!!! ComprobanteController:118 -> " . $e->getMessage());
            return $this->exceptionResponse("Hubo un error verificacion la deuda de la persona especificada");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validar datos del comprobante
            $validatedComprobante = $request->validate([
                'tipo_comprobante' => 'required|integer',
                'id_sede' => 'required|exists:sedes,id',
                'tipo' => 'required|integer',
                'id_persona' => 'required|exists:personas,id',
                'fecha_emision' => 'required|date',
                'moneda' => 'required|in:PEN,USD',
                'tipo_cambio' => 'nullable|numeric',
                'igv' => 'nullable|boolean',
                'subtotal' => 'required|numeric',
                'monto_igv' => 'required|numeric',
                'descuento' => 'required|numeric',
                'total' => 'required|numeric',
                'id_tipo_pago' => 'required|integer|exists:tipo_pagos,id',
                'pago_cliente' => 'required|numeric',
                'vuelto' => 'required|numeric',
                'deuda' => 'required|numeric',
                'id_tipo_pago_secundario' => 'nullable|integer|exists:tipo_pagos,id',
                'pago_cliente_secundario' => 'nullable|numeric',
                'detalles' => 'required|array|min:1',
                'detalles.*.id_articulo' => 'required|exists:articulos,id',
                'detalles.*.cantidad' => 'required|numeric|min:1',
                'detalles.*.precio_unitario' => 'required|numeric',
                'detalles.*.descuento' => 'nullable|numeric',
                'detalles.*.total_producto' => 'required|numeric',
            ]);

            $serie = Comprobante::tipoComprobante()[$validatedComprobante['tipo_comprobante']];
            $ultimoNumeroSerie = DB::table('comprobantes')->select('numero')->orderBy('numero','desc')->limit(1)->value('numero');
            $nuevoNumeroSerie = '00000001';
            if($ultimoNumeroSerie)
            {
                $nuevoNumero = (int) $ultimoNumeroSerie + 1;
                $nuevoNumeroSerie = str_pad($nuevoNumero, strlen($ultimoNumeroSerie),'0',STR_PAD_LEFT);
            }

            $tipo_pago_secundario = array_key_exists('id_tipo_pago_secundario',$validatedComprobante) ? $validatedComprobante['id_tipo_pago_secundario'] : null;
            $pago_secundario = array_key_exists('pago_cliente_secundario',$validatedComprobante) ? $validatedComprobante['pago_cliente_secundario'] : null;

            // Crear comprobante
            $comprobante = Comprobante::create([
                'tipo_comprobante' => $validatedComprobante['tipo_comprobante'],
                'id_sede' => $validatedComprobante['id_sede'],
                'tipo' => $validatedComprobante['tipo'],
                'id_persona' => $validatedComprobante['id_persona'],
                'serie' => $serie,
                'numero' => $nuevoNumeroSerie,
                'fecha_emision' => $validatedComprobante['fecha_emision'],
                'moneda' => $validatedComprobante['moneda'],
                'tipo_cambio' => $validatedComprobante['tipo_cambio'] ?? null,
                'igv' => $validatedComprobante['igv'] ?? false,
                'subtotal' => $validatedComprobante['subtotal'],
                'monto_igv' => $validatedComprobante['monto_igv'],
                'descuento' => $validatedComprobante['descuento'],
                'total' => $validatedComprobante['total'],
                'id_tipo_pago' => $validatedComprobante['id_tipo_pago'],
                'pago_cliente' => $validatedComprobante['pago_cliente'],
                'vuelto' => $validatedComprobante['vuelto'],
                'deuda' => $validatedComprobante['deuda'],
                'id_tipo_pago_secundario' => $tipo_pago_secundario,
                'pago_cliente_secundario' => $pago_secundario
            ]);
            
            // Crear detalles
            foreach ($validatedComprobante['detalles'] as $detalle) {
                \Log::info("CHECK DETALLE",['detalle' => $detalle]);
                DetalleComprobante::create([
                    'id_comprobante' => $comprobante->id,
                    'id_articulo' => $detalle['id_articulo'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'descuento' => $detalle['descuento'] ?? 0,
                    'total_producto' => $detalle['total_producto'],
                ]);
            }

            $voucherType = "constancia_pago_voucher";

            switch($validatedComprobante['tipo_comprobante'])
            {
                case 1:
                    $voucherType = "boleta_voucher";
                    break;
                case 2:
                    $voucherType = "factura_voucher";
                    break;
                case 3:
                    $voucherType = "nota_credito_voucher";
                    break;
                case 4:
                    $voucherType = "constancia_pago_voucher";
                    break;
            }

            $validatedComprobante['serie'] = $comprobante->serie;
            $validatedComprobante['numero_correlativo'] = $comprobante->numero;

            $url = PDFService::generateVoucher($voucherType,$validatedComprobante);
            $this->comprobanteService->handler($validatedComprobante,$comprobante);

            $comprobante["voucher_url"] = $url;
            $comprobante->save();

            CajaChica::create([
                'tipo' => 'Ingreso',
                'balance' => $comprobante['total'],
                'id_sede' => $validatedComprobante['id_sede'],
                'fecha' => $validatedComprobante['fecha_emision'],
                'motivo' => 'Venta'
            ]);

            DB::commit();

            return $this->successResponse(['voucher_url' => $url, 'success' => true]);
    
            // Recargar relaciones y devolver con el resource
            $comprobante->load(['detalles', 'persona', 'sede']);
    
            return (new ComprobanteResource($comprobante))
            ->response()
            ->setStatusCode(201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al registrar comprobante: ' . $e->getMessage(),
                'debug' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comprobante $comprobante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comprobante $comprobante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comprobante $comprobante)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'tipo_comprobante' => 'required|integer',
                'id_sede' => 'required|exists:sedes,id',
                'tipo' => 'required|integer',
                'id_persona' => 'required|exists:personas,id',
                'serie' => 'required|string|max:10',
                'numero' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::unique('comprobantes')->where(function ($query) use ($request) {
                        return $query->where('serie', $request->serie);
                    })->ignore($comprobante->id),
                ],
                'fecha_emision' => 'required|date',
                'moneda' => 'required|in:PEN,USD',
                'tipo_cambio' => 'nullable|numeric',
                'igv' => 'required|boolean',
                'subtotal' => 'required|numeric',
                'monto_igv' => 'required|numeric',
                'descuento' => 'required|numeric',
                'total' => 'required|numeric',
                'pago_cliente' => 'required|numeric',
                'vuelto' => 'required|numeric',
                'detalles' => 'required|array|min:1',
                'detalles.*.id_articulo' => 'required|exists:articulos,id',
                'detalles.*.cantidad' => 'required|numeric|min:1',
                'detalles.*.precio_unitario' => 'required|numeric',
                'detalles.*.descuento' => 'nullable|numeric',
                'detalles.*.total_producto' => 'required|numeric',
            ]);
    
            // Actualizar datos del comprobante
            $comprobante->update([
                'tipo_comprobante' => $validatedData['tipo_comprobante'],
                'id_sede' => $validatedData['id_sede'],
                'tipo' => $validatedData['tipo'],
                'id_persona' => $validatedData['id_persona'],
                'serie' => $validatedData['serie'],
                'numero' => $validatedData['numero'],
                'fecha_emision' => $validatedData['fecha_emision'],
                'moneda' => $validatedData['moneda'],
                'tipo_cambio' => $validatedData['tipo_cambio'] ?? null,
                'igv' => $validatedData['igv'],
                'subtotal' => $validatedData['subtotal'],
                'monto_igv' => $validatedData['monto_igv'],
                'descuento' => $validatedData['descuento'],
                'total' => $validatedData['total'],
                'pago_cliente' => $validatedData['pago_cliente'],
                'vuelto' => $validatedData['vuelto'],
            ]);
    
            // Eliminar detalles anteriores
            $comprobante->detalles()->delete();
    
            // Crear nuevos detalles
            foreach ($validatedData['detalles'] as $detalle) {
                DetalleComprobante::create([
                    'id_comprobante' => $comprobante->id,
                    'id_articulo' => $detalle['id_articulo'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'descuento' => $detalle['descuento'] ?? 0,
                    'total_producto' => $detalle['total_producto'],
                ]);
            }
    
            DB::commit();
    
            // Recargar relaciones y devolver con recurso
            $comprobante->load(['detalles', 'persona', 'sede']);
    
            return (new ComprobanteResource($comprobante))
            ->response()
            ->setStatusCode(200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar comprobante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comprobante $comprobante)
    {
        try {
            $comprobante->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar comprobante: ' . $e->getMessage()
            ], 500);
        }
    }
}

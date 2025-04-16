<?php

namespace App\Http\Controllers;

use App\Http\Resources\ComprobanteResource;
use App\Models\Comprobante;
use App\Models\DetalleComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comprobantes = Comprobante::with(['persona', 'sede', 'detalles'])
        ->orderBy('fecha_emision', 'desc')
        ->get();

        return ComprobanteResource::collection($comprobantes)
        ->response()
        ->setStatusCode(200);
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
                'serie' => 'required|string|max:10',
                'numero' => 'required|string|max:10|unique:comprobantes,numero',
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
    
            // Crear comprobante
            $comprobante = Comprobante::create([
                'tipo_comprobante' => $validatedComprobante['tipo_comprobante'],
                'id_sede' => $validatedComprobante['id_sede'],
                'tipo' => $validatedComprobante['tipo'],
                'id_persona' => $validatedComprobante['id_persona'],
                'serie' => $validatedComprobante['serie'],
                'numero' => $validatedComprobante['numero'],
                'fecha_emision' => $validatedComprobante['fecha_emision'],
                'moneda' => $validatedComprobante['moneda'],
                'tipo_cambio' => $validatedComprobante['tipo_cambio'] ?? null,
                'igv' => $validatedComprobante['igv'],
                'subtotal' => $validatedComprobante['subtotal'],
                'monto_igv' => $validatedComprobante['monto_igv'],
                'descuento' => $validatedComprobante['descuento'],
                'total' => $validatedComprobante['total'],
                'pago_cliente' => $validatedComprobante['pago_cliente'],
                'vuelto' => $validatedComprobante['vuelto'],
            ]);
    
            // Crear detalles
            foreach ($validatedComprobante['detalles'] as $detalle) {
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
    
            // Recargar relaciones y devolver con el resource
            $comprobante->load(['detalles', 'persona', 'sede']);
    
            return (new ComprobanteResource($comprobante))
            ->response()
            ->setStatusCode(201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al registrar comprobante: ' . $e->getMessage()
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
                'numero' => 'required|string|max:10|unique:comprobantes,numero,' . $comprobante->id,
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

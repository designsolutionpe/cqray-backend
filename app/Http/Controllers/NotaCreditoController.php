<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotaCreditoResource;
use App\Models\NotaCredito;
use App\Models\DetalleNota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotaCreditoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notasCredito = NotaCredito::with(['sede', 'comprobante', 'detalles.articulo'])
            ->orderBy('fecha_emision', 'desc')
            ->get();

        return NotaCreditoResource::collection($notasCredito)
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

    public function getLastItem()
    {
        $comp = NotaCredito::all()->last();
        return response()->json($comp,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
    
        try {
            // Validar datos de la nota de crédito
            $validatedNotaCredito = $request->validate([
                'id_comprobante' => 'required|exists:comprobantes,id',
                'tipo_comprobante' => 'required|integer',
                'id_sede' => 'required|exists:sedes,id',
                'tipo' => 'required|integer',
                'motivo' => 'required|integer',
                'comentario' => 'nullable|string',
                'serie' => 'required|string',
                'numero' => 'required|string',
                'fecha_emision' => 'required|date',
                'moneda' => 'required|in:PEN,USD',
                'tipo_cambio' => 'nullable|numeric',
                'igv' => 'required|boolean',
                'subtotal' => 'required|numeric',
                'monto_igv' => 'required|numeric',
                'descuento' => 'required|numeric',
                'total' => 'required|numeric',
                'detalles' => 'required|array|min:1',
                'detalles.*.id_articulo' => 'required|exists:articulos,id',
                'detalles.*.cantidad' => 'required|numeric|min:1',
                'detalles.*.precio_unitario' => 'required|numeric',
                'detalles.*.descuento' => 'nullable|numeric',
                'detalles.*.total_producto' => 'required|numeric',
            ]);
    
            // Crear nota de crédito
            $notaCredito = NotaCredito::create([
                'id_comprobante' => $validatedNotaCredito['id_comprobante'],
                'tipo_comprobante' => $validatedNotaCredito['tipo_comprobante'],
                'id_sede' => $validatedNotaCredito['id_sede'],
                'tipo' => $validatedNotaCredito['tipo'],
                'motivo' => $validatedNotaCredito['motivo'],
                'comentario' => $validatedNotaCredito['comentario'] ?? null,
                'serie' => $validatedNotaCredito['serie'],
                'numero' => $validatedNotaCredito['numero'],
                'fecha_emision' => $validatedNotaCredito['fecha_emision'],
                'moneda' => $validatedNotaCredito['moneda'],
                'tipo_cambio' => $validatedNotaCredito['tipo_cambio'] ?? null, // Puede ser nulo
                'igv' => $validatedNotaCredito['igv'],
                'subtotal' => $validatedNotaCredito['subtotal'],
                'monto_igv' => $validatedNotaCredito['monto_igv'],
                'descuento' => $validatedNotaCredito['descuento'],
                'total' => $validatedNotaCredito['total'],
            ]);
    
            // Crear detalles de la nota de crédito
            foreach ($validatedNotaCredito['detalles'] as $detalle) {
                DetalleNota::create([
                    'id_nota_credito' => $notaCredito->id,
                    'id_articulo' => $detalle['id_articulo'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'descuento' => $detalle['descuento'] ?? 0,
                    'total_producto' => $detalle['total_producto'],
                ]);
            }
    
            DB::commit();
    
            // Recargar relaciones y devolver con el resource
            $notaCredito->load(['detalles', 'comprobante', 'sede']);
    
            return (new NotaCreditoResource($notaCredito))
                ->response()
                ->setStatusCode(201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al registrar la nota de crédito: ' . $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(NotaCredito $notaCredito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotaCredito $notaCredito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NotaCredito $notaCredito)
    {
        DB::beginTransaction();
    
        try {
            // Validar datos de la nota de crédito
            $validatedData = $request->validate([
                'id_comprobante' => 'required|exists:comprobantes,id',
                'tipo_comprobante' => 'required|integer',
                'id_sede' => 'required|exists:sedes,id',
                'tipo' => 'required|integer',
                'motivo' => 'required|integer',
                'comentario' => 'nullable|string',
                'serie' => 'required|string',
                'numero' => 'required|string',
                'fecha_emision' => 'required|date',
                'moneda' => 'required|in:PEN,USD',
                'tipo_cambio' => 'nullable|numeric',
                'igv' => 'required|boolean',
                'subtotal' => 'required|numeric',
                'monto_igv' => 'required|numeric',
                'descuento' => 'required|numeric',
                'total' => 'required|numeric',
                'detalles' => 'required|array|min:1',
                'detalles.*.id_articulo' => 'required|exists:articulos,id',
                'detalles.*.cantidad' => 'required|numeric|min:1',
                'detalles.*.precio_unitario' => 'required|numeric',
                'detalles.*.descuento' => 'nullable|numeric',
                'detalles.*.total_producto' => 'required|numeric',
            ]);
    
            // Actualizar nota de crédito
            $notaCredito->update([
                'id_comprobante' => $validatedData['id_comprobante'],
                'tipo_comprobante' => $validatedData['tipo_comprobante'],
                'id_sede' => $validatedData['id_sede'],
                'tipo' => $validatedData['tipo'],
                'motivo' => $validatedData['motivo'],
                'comentario' => $validatedData['comentario'] ?? null,
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
            ]);
    
            // Eliminar detalles anteriores
            $notaCredito->detalles()->delete();
    
            // Crear nuevos detalles
            foreach ($validatedData['detalles'] as $detalle) {
                DetalleNota::create([
                    'id_nota_credito' => $notaCredito->id,
                    'id_articulo' => $detalle['id_articulo'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'descuento' => $detalle['descuento'] ?? 0,
                    'total_producto' => $detalle['total_producto'],
                ]);
            }
    
            DB::commit();
    
            // Recargar relaciones y devolver con recurso
            $notaCredito->load(['detalles', 'comprobante', 'sede']);
    
            return (new NotaCreditoResource($notaCredito))
                ->response()
                ->setStatusCode(200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar la nota de crédito: ' . $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotaCredito $notaCredito)
    {
        try {
            $notaCredito->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar la nota de crédito: ' . $e->getMessage()
            ], 500);
        }
    }
}

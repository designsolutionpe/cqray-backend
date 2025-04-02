<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Pago::with('sede')->get(), 200);
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
        $validatedData = $request->validate([
            'id_sede' => 'required|exists:sedes,id',
            'metodo_pago' => 'required|in:Transferencia,Efectivo,Plin,Yape',
            'moneda' => 'required|in:PEN,USD',
            'detalle_concepto' => 'nullable|string',
            'numero_cuenta' => 'nullable|string|max:50',
            'estado' => 'required|in:0,1',
        ]);
    
        $pago = Pago::create($validatedData);
        return response()->json($pago, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        $validatedData = $request->validate([
            'id_sede' => 'required|exists:sedes,id',
            'metodo_pago' => 'required|in:Transferencia,Efectivo,Plin,Yape',
            'moneda' => 'required|in:PEN,USD',
            'detalle_concepto' => 'nullable|string',
            'numero_cuenta' => 'nullable|string|max:50',
            'estado' => 'required|in:0,1',
        ]);
    
        $pago->update($validatedData);
        return response()->json($pago, 200);
    }

    public function cambiarEstado(Request $request, Pago $pago)
    {
        $request->validate([
            'estado' => 'required|in:0,1'
        ]);
    
        $pago->estado = $request->estado;
        $pago->save();
    
        return response()->json(['message' => 'Estado actualizado correctamente.', 
        'estado' => $pago->estado], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        try {
            $pago->delete();
            return response()->json(['message' => 'Pago eliminado correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el pago: ' . $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\TipoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $all = TipoPago::where('activo',1)->get();
        return response()->json($all,200);
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
        //
        $validated = $request->validate([
            'nombre' => 'required|string',
        ]);

        $tipo = TipoPago::create($request->all());
        return response()->json($tipo,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoPago $tipoPago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoPago $tipoPago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoPago $tipoPago)
    {
        //
        DB::beginTransaction();
        try
        {
            $validated = $request->validate([
                'nombre' => 'required|string',
            ]);
    
            $tipo = $tipoPago->update($request->all());

            DB::commit();
            return response()->json($tipo,200);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json(["error"=>"Error al actualizar tipo de pago: " . $e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoPago $tipoPago)
    {
        //
        DB::beginTransaction();
        try
        {
            $tipoPago->delete();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['error'=>'Error al eliminar el tipo de pago: '.$e->getMessage()],500);
        }
    }
}

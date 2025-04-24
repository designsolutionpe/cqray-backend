<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UnidadMedidaArticulo;

class UnidadMedidaArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $all = UnidadMedidaArticulo::all();
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
        DB::beginTransaction();
        try
        {
            $validated = $request->validate([
                'nombre' => 'required|string',
            ]);

            $unidad = UnidadMedida::create($validated);
            DB::commit();
            return response()->json($unidad,201);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['error'=>'Error al crear la unidad de medida'],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UnidadMedidaArticulo $unidadMedidaArticulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnidadMedidaArticulo $unidadMedidaArticulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnidadMedidaArticulo $unidadMedidaArticulo)
    {
        //
        DB::beginTransaction();
        try
        {
            $validated = $request->validate([
                'nombre' => 'required|string',
            ]);

            $unidadMedidaArticulo->update($validated);

            DB::commit();
            return response()->json($unidadMedidaArticulo,200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['error'=>'Error al crear la unidad de medida'],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnidadMedidaArticulo $unidadMedidaArticulo)
    {
        //
        DB::beginTransaction();
        try
        {
            $unidadMedidaArticulo->delete();
            DB::commit();
            return response()->json(['message'=>'La unidad de medida fue eliminada con exito'],200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['error'=>'Error al crear la unidad de medida'],500);
        }
    }
}

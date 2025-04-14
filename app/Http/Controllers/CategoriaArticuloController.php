<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaArticulo;
use Illuminate\Support\Facades\DB;

class CategoriaArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categorias = CategoriaArticulo::all();
        return response()->json($categorias,200);
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
                'estado' => 'required|integer|in:1,2'
            ]);

            $categoria = CategoriaArticulo::create($validated);

            DB::commit();

            return response()->json(['categoria'=>$categoria],201);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json(['error'=>'Error al crear la categoria: ' . $e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriaArticulo $categoriaArticulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoriaArticulo $categoriaArticulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoriaArticulo $categoriaArticulo)
    {
        //
        DB::beginTransaction();
        try
        {
            $validated = $request->validate([
                'nombre' => 'required|string',
                'estado' => 'required|integer|in:1,2'
            ]);

            $categoriaArticulo->update($validated);

            DB::commit();

            return response()->json(['categoria'=>$categoriaArticulo],200);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json(['error'=>'Error al actualizar la categoria: ' . $e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoriaArticulo $categoriaArticulo)
    {
        //
        DB::beginTransaction();
        try
        {
            $categoriaArticulo->delete();
            DB::commit();
            return response()->json(['message'=>'La categoria ha sido eliminada correctamente'],200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['Error'=>'Error al eliminar la categoria: ' . $e->getMessage()],500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CajaChica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaChicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $items = CajaChica::with(['sede']);

        if($request->filled("sede"))
            $items->where("id_sede",$request->query("sede"));

        if($request->filled("tipo"))
            $items->where("tipo",$request->query("tipo"));

        if($request->filled("fecha"))
            $items->where("fecha",$request->query("fecha"));

        $result = $items->get();

        if( $request->query("tipo") == "Egreso" && count($result) == 0 )
        {}

        return response()->json($items->get(),200);
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
            $estado = $request->query('estado');
            $validated = $request->validate([
                'tipo' => 'required|string|in:Ingreso,Egreso',
                'balance' => 'required|numeric|min:0',
                'id_sede' => 'required|integer|exists:sedes,id',
                'fecha' => 'required|string'
            ]);

            $validated['flg_inicial'] = ($estado == 'i');
            $validated['flg_terminal'] = ($estado == 't');

            $item = CajaChica::create($validated);
            DB::commit();
            return response()->json($item,201);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['message'=>'Hubo un error creando el registro. ' . $e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CajaChica $cajaChica)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CajaChica $cajaChica)
    {
        //
        DB::beginTransaction();
        try
        {

            $validated = $request->validate([
                'inicial' => 'required|numeric',
                'estado' => 'required|string|in:Ingreso,Egreso',
                'balance' => 'required|numeric|min:0',
                'cerrado_en' => 'required|string',
                'id_sede' => 'nullable|integer|exists:sedes,id'
            ]);
            $cajaChica->update($validated);
            DB::commit();
            return response()->json($cajaChica,200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['error'=>'No se pudo actualizar el registro '.$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CajaChica $cajaChica)
    {
        //
        DB::beginTransaction();
        try
        {
            $cajaChica->delete();
            DB::commit();
            return response()->json(['message'=>'Se ha eliminado correctamente'],200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['error'=>'No se pudo eliminar el registro '. $e->getMessage()],500);
        }
    }
}

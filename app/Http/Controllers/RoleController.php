<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $estado = $request->query('estado',null); // ESTADO DEBE SER 1: ACTIVO - 2: INACTIVO
        if(isset($estado))
            $roles = Role::where('activo',1)->get();
        else
            $roles = Role::all();
        return response()->json($roles,200);
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
                'activo' => 'required|integer|in:0,1'
            ]);
    
            $role = Role::create($validated);

            DB::commit();
            return response()->json($role,201);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['error'=>'Error al crear rol: ' . $e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
        DB::beginTransaction();
        try
        {
            $validated = $request->validate([
                'nombre' => 'required|string',
                'activo' => 'required|integer|in:0,1'
            ]);

            $role->update($validated);
            DB::commit();
            return response()->json(['message'=>'El rol ha sido actualizado con exito'],200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['error'=>'Error al actualizar el rol: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
        DB::beginTransaction();
        try
        {
            $role->delete();
            DB::commit();
            return response()->json(['message'=>'El rol ha sido eliminado con exito'],200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['error'=>'Error al eliminar el rol: ' . $e->getMessage()],500);
        }
    }
}

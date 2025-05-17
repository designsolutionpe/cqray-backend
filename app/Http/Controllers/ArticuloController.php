<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $articulos = Articulo::with(['sede','categoria','unidad_medida'])->get();
        return response()->json($articulos,200);
    }

    public function buscar(Request $request)
    {
        // Validación de los parámetros
        $validated = $request->validate([
            'id_sede' => 'required|integer',
            'tipo_articulo' => 'required|integer|in:1,2', // 1: Producto, 2: Servicio
        ]);

        $articulos = Articulo::where('id_sede', $validated['id_sede'])
                             ->where('tipo_articulo', $validated['tipo_articulo'])
                             ->select('id', 'nombre', 'cantidad', 'precio_venta', 'precio_mayor', 'precio_distribuidor', 'precio_compra')
                             ->get();

        return response()->json($articulos, 200);
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
            $rules = [
                'id_sede' => 'required|integer|exists:sedes,id',
                'id_categoria' => 'required|integer|exists:categoria_articulos,id',
                'id_estado_paciente' => 'nullable|integer|exists:estado_pacientes,id',
                'tipo_articulo' => 'required|integer|in:1,2',
                'id_unidad_medida' => 'required|integer|exists:unidad_medida_articulos,id',
                'nombre' => 'required|string',
                'detalle' => 'required|string',
                'cantidad' => 'required|integer',
                'limite_cantidad' => 'nullable|integer',
                'precio_venta' => 'required|numeric:10,2',
                'precio_mayor' => 'nullable|numeric:10,2',
                'precio_distribuidor' => 'nullable|numeric:10,2',
                'precio_compra' => 'nullable|numeric:10,2',
                'tipo_tributo' => 'nullable|string',
                'tributo' => 'nullable|string',
                'codigo_internacional' => 'nullable|string',
                'nombre_tributo' => 'nullable|string'
            ];
    
            $validated = $request->validate($rules);
    
            $articulo = Articulo::create($request->all());

            DB::commit();

            return response()->json($articulo,201);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['Error'=>'Error creando articulo: ' . $e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Articulo $articulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Articulo $articulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Articulo $articulo)
    {
        //
        DB::beginTransaction();
        try
        {
            $rules = [
                'id_sede' => 'required|integer|exists:sedes,id',
                'id_categoria' => 'required|integer|exists:categoria_articulos,id',
                'id_estado_paciente' => 'nullable|integer|exists:estado_pacientes,id',
                'tipo_articulo' => 'required|integer|in:1,2',
                'id_unidad_medida' => 'required|integer|exists:unidad_medida_articulos,id',
                'nombre' => 'required|string',
                'detalle' => 'required|string',
                'cantidad' => 'required|integer',
                'limite_cantidad' => 'nullable|integer',
                'precio_venta' => 'required|numeric:10,2',
                'precio_mayor' => 'nullable|numeric:10,2',
                'precio_distribuidor' => 'nullable|numeric:10,2',
                'precio_compra' => 'nullable|numeric:10,2',
                'tipo_tributo' => 'nullable|string',
                'tributo' => 'nullable|string',
                'codigo_internacional' => 'nullable|string',
                'nombre_tributo' => 'nullable|string'
            ];
    
            $validated = $request->validate($rules);
    
            $articulo->update($request->all());

            DB::commit();

            return response()->json($articulo,200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['Error'=>'Error actualizando articulo: '.$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Articulo $articulo)
    {
        //
        DB::beginTransaction();
        try
        {
            $articulo->delete();

            DB::commit();

            return response()->json(['message'=>'Eliminado correctamente'],200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['ERROR'=>'Error al eliminar articulo: ' . $e->getMessage()],500);
        }
    }
}

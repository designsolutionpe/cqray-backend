<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Configuracion::all(), 200);
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
        // Validación de datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'required|string|max:20',
            'numero_sucursales' => 'required|integer',
            'imagen1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'required|integer'
        ]);

        if ($request->hasFile('imagen1')) {
            $rutaImagen1 = $request->file('imagen1')->store('configuraciones', 'public');
            $validatedData['imagen1'] = $rutaImagen1;
        }

        if ($request->hasFile('imagen2')) {
            $rutaImagen2 = $request->file('imagen2')->store('configuraciones', 'public');
            $validatedData['imagen2'] = $rutaImagen2;
        }

        // Crear la configuración con los datos validados
        $configuracion = Configuracion::create($validatedData);

        // Retornar la configuración creada con código 201 (Created)
        return response()->json($configuracion, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Configuracion $configuracion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Configuracion $configuracion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Configuracion $configuracion)
    {
        //
        $validatedData = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'ruc' => 'sometimes|required|string|max:20',
            'numero_sucursales' => 'sometimes|required|integer',
            'imagen1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'estado' => 'sometimes|required|integer'
        ]);

        if ($configuracion->imagen1){
            if (Storage::exists($configuracion->imagen1)) {
                Storage::delete($configuracion->imagen1);
            }
            $validatedData['imagen1'] = null;
        }

        if ($configuracion->imagen2){
            if (Storage::exists($configuracion->imagen2)) {
                Storage::delete($configuracion->imagen2);
            }
            $validatedData['imagen2'] = null;
        }

        if ($request->hasFile('imagen1')) {
            $rutaImagen1 = $request->file('imagen1')->store('configuraciones', 'public');
            $validatedData['imagen1'] = $rutaImagen1;
        }
        if ($request->hasFile('imagen2')) {
            $rutaImagen2 = $request->file('imagen2')->store('configuraciones', 'public');
            $validatedData['imagen2'] = $rutaImagen2;
        }

        // Actualizar la configuración con los datos validados
        $configuracion->update($validatedData);

        return response()->json($configuracion, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Configuracion $configuracion)
    {
        $configuracion->delete();
        return response()->json(
            ['message' => 'Configuración eliminada correctamente'], 
            200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SedeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Sede::all(), 200);
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
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $rutaImagen = $request->file('foto')->store('sedes', 'public');
            $validatedData['foto'] = $rutaImagen;
        }

        // Crear la sede con los datos validados
        $sede = Sede::create($validatedData);

        // Retornar la sede creada con código 201 (Created)
        return response()->json($sede, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sede $sede)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sede $sede)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sede $sede)
    {
        //
        $validatedData = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        if ($sede->foto){
            if (Storage::exists($sede->foto)) {
                Storage::delete($sede->foto);
            }
            $validatedData['foto'] = null;
        }

        if ($request->hasFile('foto')) {
            $rutaImagen = $request->file('foto')->store('sedes', 'public');
            $validatedData['foto'] = $rutaImagen;
        }

        // Actualizar la sede
        $sede->update($validatedData);
    
        // Retornar la sede actualizada
        return response()->json($sede, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sede $sede)
    {
        $sede->delete();
        // Retornar respuesta con código 204 (No Content)
        return response()->json(null, 204);
    }
}

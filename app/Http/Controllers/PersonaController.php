<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Persona::all(), 200);
    }

    public function searchPersonas(Request $request)
    {
        // Obtener los parámetros de búsqueda
        $numeroDocumento = $request->get('numero_documento', null);
        $nombre = $request->get('nombre', null);
    
        // Validar que al menos uno de los dos parámetros esté presente
        if (is_null($numeroDocumento) && is_null($nombre)) {
            return response()->json([], 200); // Si ambos están vacíos, devolver lista vacía
        }
    
        // Crear la consulta base
        $query = Persona::query();
    
        // Filtrar por número de documento si se proporciona
        if ($numeroDocumento) {
            $query->whereRaw('LOWER(numero_documento) LIKE ?', ['%' . strtolower($numeroDocumento) . '%']);
        }
    
        // Filtrar por nombre o apellido si se proporciona
        if ($nombre) {
            $query->where(function ($subQuery) use ($nombre) {
                $subQuery->whereRaw('LOWER(apellido) LIKE ?', ['%' . strtolower($nombre) . '%'])
                         ->orWhereRaw('LOWER(nombre) LIKE ?', ['%' . strtolower($nombre) . '%']);
            });
        }
    
        // Ejecutar la consulta y obtener los resultados
        $personas = $query->select([
            'id',
            'tipo_documento',
            'numero_documento',
            DB::raw("CONCAT(apellido, ' ', nombre) AS nombreCompleto"),
            'email',
            'foto'
        ])->get();
    
        return response()->json($personas, 200);
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
            'tipo_documento' => 'required|in:' . implode(',', Persona::tipoDocumentos()),
            'numero_documento' => 'required|string|max:20|unique:personas,numero_documento',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'genero' => 'nullable|in:' . implode(',', Persona::generos()),
            'fecha_nacimiento' => 'nullable|date',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:personas,email',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $rutaImagen = $request->file('foto')->store('personas', 'public');
            $validatedData['foto'] = $rutaImagen;
        }

        $persona = Persona::create($validatedData);
        return response()->json($persona, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Persona $persona)
    {
        $validatedData = $request->validate([
            'tipo_documento' => 'sometimes|required|in:' . implode(',', Persona::tipoDocumentos()),
            'numero_documento' => 'sometimes|required|string|max:20|unique:personas,numero_documento,' . $persona->id,
            'nombre' => 'sometimes|required|string|max:255',
            'apellido' => 'sometimes|required|string|max:255',
            'genero' => 'sometimes|nullable|in:' . implode(',', Persona::generos()),
            'fecha_nacimiento' => 'sometimes|nullable|date',
            'direccion' => 'sometimes|nullable|string|max:255',
            'telefono' => 'sometimes|nullable|string|max:20',
            'email' => 'sometimes|nullable|email|max:255|unique:personas,email,' . $persona->id,
        ]);

        if ($persona->foto){
            if (Storage::exists($persona->foto)) {
                Storage::delete($persona->foto);
            }
            $validatedData['foto'] = null;
        }

        if ($request->hasFile('foto')) {
            $rutaImagen = $request->file('foto')->store('personas', 'public');
            $validatedData['foto'] = $rutaImagen;
        }

        $persona->update($validatedData);
        return response()->json($persona, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        if ($persona->paciente || $persona->quiropractico) {
            return response()->json([
                'error' => 'No se puede eliminar esta persona porque está asociada a un paciente o quiropractico.'
            ], 400);
        }
    
        // Eliminar la persona
        if ($persona->foto) {
            if (Storage::exists($persona->foto)) {
                Storage::delete($persona->foto);
            }
        }
        $persona->delete();
        return response()->json(['message' => 'Persona eliminada'], 200);
    }
}

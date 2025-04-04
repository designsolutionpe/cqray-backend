<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use App\Models\Quiropractico;

class QuiropracticoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Quiropractico::with('persona', 'sede')->get(), 200);
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
        DB::beginTransaction();
        //
        try {
            $validatedPersona  = $request->validate([
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
                $validatedPersona['foto'] = $rutaImagen;
            }
        
            $persona = Persona::create($validatedPersona);

            // Validar datos de quiropractico
            $validatedQuiropractico = $request->validate([
                'id_sede' => 'required|exists:sedes,id',
                'numero_colegiatura' => 'nullable|string|max:20|unique:quiropracticos,numero_colegiatura',
                'especialidad' => 'nullable|string|max:255',
                'datos_contacto' => 'nullable|string|max:255',
                'estado' => 'required|integer|in:0,1,2',
            ]);

            $validatedQuiropractico['id_persona'] = $persona->id;
            $quiropractico = Quiropractico::create($validatedQuiropractico);

            DB::commit();
            return response()->json([
                'persona' => $persona,
                'quiropractico' => $quiropractico
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear quiropractico: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiropractico $quiropractico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiropractico $quiropractico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiropractico $quiropractico)
    {
        DB::beginTransaction();
        try {
            $validatedPersona = Validator::make($request->all(), [
                'tipo_documento' => 'required|in:' . implode(',', Persona::tipoDocumentos()),
                'numero_documento' => [
                    'required', 'string', 'max:20',
                    Rule::unique('personas', 'numero_documento')->ignore($quiropractico->persona->id),
                ],
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'genero' => 'nullable|in:' . implode(',', Persona::generos()),
                'fecha_nacimiento' => 'nullable|date',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'email' => [
                    'nullable', 'email', 'max:255',
                    Rule::unique('personas', 'email')->ignore($quiropractico->persona->id),
                ],
            ])->validate();
            
            // Validar los datos del Quiropractico
            $validatedQuiropractico = Validator::make($request->all(), [
                'id_sede' => 'required|exists:sedes,id',
                'numero_colegiatura' => [
                    'nullable', 'string', 'max:20',
                    Rule::unique('quiropracticos', 'numero_colegiatura')->ignore($quiropractico->id),
                ],
                'especialidad' => 'nullable|string|max:255',
                'datos_contacto' => 'nullable|string|max:255',
                'estado' => 'required|integer|in:0,1,2',
            ])->validate();

            // Eliminar la imagen
            if ($quiropractico->persona->foto) {
                if (Storage::exists($quiropractico->persona->foto)) {
                    Storage::delete($quiropractico->persona->foto);
                }
                $validatedPersona['foto'] = null;
            }

            // Verificar si se subió una nueva imagen
            if ($request->hasFile('foto')) {
                $rutaImagen = $request->file('foto')->store('personas', 'public');
                $validatedPersona['foto'] = $rutaImagen;
            }

            // Actualizar persona
            $quiropractico->persona->update($validatedPersona);

            // Actualizar quiropractico
            $quiropractico->update($validatedQuiropractico);

            DB::commit();
            return response()->json([
                'persona' => $quiropractico->persona,
                'quiropractico' => $quiropractico
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar quiropractico: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiropractico $quiropractico)
    {
        DB::beginTransaction();

        try {
            $persona = $quiropractico->persona;
            if ($persona && $persona->foto) {
                Storage::delete($persona->foto);
            }
            $quiropractico->delete();
            if ($persona && !$persona->quiropractico()->exists()) {
                $persona->delete();
            }
            DB::commit();
            return response()->json(['message' => 'Quiropractico eliminado'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar quiropractico: ' . $e->getMessage()], 500);
        }
    }
}

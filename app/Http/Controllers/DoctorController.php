<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Doctor::with('persona', 'sede')->get(), 200);
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

            // Validar datos de doctor
            $validatedDoctor = $request->validate([
                'id_sede' => 'required|exists:sedes,id',
                'numero_colegiatura' => 'nullable|string|max:20|unique:doctores,numero_colegiatura',
                'especialidad' => 'nullable|string|max:255',
                'datos_contacto' => 'nullable|string|max:255',
                'estado' => 'required|integer|in:0,1,2',
            ]);

            $validatedDoctor['id_persona'] = $persona->id;
            $doctor = Doctor::create($validatedDoctor);

            DB::commit();
            return response()->json([
                'persona' => $persona,
                'doctor' => $doctor
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear doctor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        DB::beginTransaction();
        try {
            $validatedPersona = Validator::make($request->all(), [
                'tipo_documento' => 'required|in:' . implode(',', Persona::tipoDocumentos()),
                'numero_documento' => [
                    'required', 'string', 'max:20',
                    Rule::unique('personas', 'numero_documento')->ignore($doctor->persona->id),
                ],
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'genero' => 'nullable|in:' . implode(',', Persona::generos()),
                'fecha_nacimiento' => 'nullable|date',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'email' => [
                    'nullable', 'email', 'max:255',
                    Rule::unique('personas', 'email')->ignore($doctor->persona->id),
                ],
            ])->validate();
            
            // Validar los datos del Doctor
            $validatedDoctor = Validator::make($request->all(), [
                'id_sede' => 'required|exists:sedes,id',
                'numero_colegiatura' => [
                    'nullable', 'string', 'max:20',
                    Rule::unique('doctores', 'numero_colegiatura')->ignore($doctor->id),
                ],
                'especialidad' => 'nullable|string|max:255',
                'datos_contacto' => 'nullable|string|max:255',
                'estado' => 'required|integer|in:0,1,2',
            ])->validate();

            // Eliminar la imagen
            if ($doctor->persona->foto) {
                if (Storage::exists($doctor->persona->foto)) {
                    Storage::delete($doctor->persona->foto);
                }
                $validatedPersona['foto'] = null;
            }

            // Verificar si se subió una nueva imagen
            if ($request->hasFile('foto')) {
                $rutaImagen = $request->file('foto')->store('personas', 'public');
                $validatedPersona['foto'] = $rutaImagen;
            }

            // Actualizar persona
            $doctor->persona->update($validatedPersona);

            // Actualizar doctor
            $doctor->update($validatedDoctor);

            DB::commit();
            return response()->json([
                'persona' => $doctor->persona,
                'doctor' => $doctor
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar doctor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        DB::beginTransaction();

        try {
            $persona = $doctor->persona;
            if ($persona && $persona->foto) {
                Storage::delete($persona->foto);
            }
            $doctor->delete();
            if ($persona && !$persona->doctor()->exists()) {
                $persona->delete();
            }
            DB::commit();
            return response()->json(['message' => 'Doctor eliminado'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar doctor: ' . $e->getMessage()], 500);
        }
    }
}

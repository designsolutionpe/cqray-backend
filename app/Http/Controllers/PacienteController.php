<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Paciente::with('persona', 'sede','estado')->get(), 200);
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

            // Validar datos de paciente
            $validatedPaciente = $request->validate([
                'id_sede' => 'required|exists:sedes,id',
                'historia_clinica' => 'nullable|integer',
                'estado' => 'required|integer|exists:estado_pacientes,id',
            ]);

            // Crear paciente y asociarlo a la persona
            $validatedPaciente['id_persona'] = $persona->id;
            $paciente = Paciente::create($validatedPaciente);

            DB::commit();
            return response()->json([
                'persona' => $persona,
                'paciente' => $paciente,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear paciente: ' . $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {
        DB::beginTransaction();
        try
        {
            $obj = $paciente->load(['persona','sede','citas','estado','citas.sede','citas.estado']);
            
            $paciente_created = [
                "titulo" => "Paciente Creado",
                "fecha" => $paciente->created_at
            ];

            $citas = $obj['citas']->toArray();

            $citas_evento = array_map( function($e){
                $sede = explode(' ',$e['sede']['nombre']);
                return [
                    "titulo" => "Cita",
                    "fecha" => $e['created_at'],
                    "atendido" => "Quiropractico - " . ($sede[1] ?? '')
                ];
            }, $citas);

            $obj['events'] = array_merge([],[$paciente_created],$citas_evento);

            return response()->json($obj,200);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json(["error"=>"Error al obtener informacion del paciente: " . $e->getMessage()],500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paciente $paciente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paciente $paciente)
    {

        DB::beginTransaction();
        try {
            // Validar los datos de la Persona (sin anidamiento)
            $validatedPersona = Validator::make($request->all(), [
                'tipo_documento' => 'required|in:' . implode(',', Persona::tipoDocumentos()),
                'numero_documento' => [
                    'required', 'string', 'max:20',
                    Rule::unique('personas', 'numero_documento')->ignore($paciente->persona->id),
                ],
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'genero' => 'nullable|in:' . implode(',', Persona::generos()),
                'fecha_nacimiento' => 'nullable|date',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'email' => [
                    'nullable', 'email', 'max:255',
                    Rule::unique('personas', 'email')->ignore($paciente->persona->id),
                ],
            ])->validate();
    
            // Validar los datos del Paciente
            $validatedPaciente = Validator::make($request->all(), [
                'id_sede' => 'required|exists:sedes,id',
                'historia_clinica' => 'nullable|integer',
                'estado' => 'required|integer|exists:estado_pacientes,id',
            ])->validate();

            // Eliminar la imagen anterior si existe
            if ($paciente->persona->foto) {
                if (Storage::exists($paciente->persona->foto)) {
                    Storage::delete($paciente->persona->foto);
                }
                $validatedPersona['foto'] = null;
            }
    
            // Verificar si se subió una nueva imagen
            if ($request->hasFile('foto')) {
                $rutaImagen = $request->file('foto')->store('personas', 'public');
                $validatedPersona['foto'] = $rutaImagen;
            }
    
            // Actualizar los datos de la Persona
            $paciente->persona->update($validatedPersona);
    
            // Actualizar los datos del Paciente
            $paciente->update($validatedPaciente);
    
            DB::commit();
    
            return response()->json([
                'persona' => $paciente->persona,
                'paciente' => $paciente,
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar el paciente: ' . $e->getMessage()
            ], 500);
        }
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {

        // Verificar si el paciente tiene citas asociadas
        if ($paciente->citas()->exists()) {
            return response()->json(
                ['error' => 'Este paciente tiene citas asociadas y no se puede eliminar.'],
                 400);
        }

        DB::beginTransaction();

        try {
            $persona = $paciente->persona;
            if ($persona && $persona->foto) {
                Storage::delete($persona->foto);
            }
            $paciente->delete();
            if ($persona && !$persona->paciente()->exists()) {
                $persona->delete();
            }
            DB::commit();
            return response()->json(['message' => 'Paciente eliminado correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar el paciente: ' . $e->getMessage()], 500);
        }
    }
}

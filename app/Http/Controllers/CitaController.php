<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\DetalleHorario;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(
            Cita::with('paciente.persona','paciente.estado', 'quiropractico.persona', 'sede','estado','tipo_paciente','usuario','usuario.sede')->get(),
            200);
    }

    public function indexByFechaYSede(Request $request)
    {
        // Obtener los parámetros de la solicitud
        $fecha = $request->input('fecha'); // Parámetro obligatorio: fecha
        $idSede = $request->input('id_sede'); // Parámetro opcional: id_sede
    
        // Iniciar la consulta
        $query = DB::table('citas as c')
            ->join('pacientes as p', 'c.id_paciente', '=', 'p.id')
            ->join('personas as pe', 'p.id_persona', '=', 'pe.id')
            ->join('estado_citas as ec', 'c.estado', '=', 'ec.id')
            ->join('estado_pacientes as ep', 'c.tipo_paciente', '=', 'ep.id')
            ->join('sedes as s', 'c.id_sede', '=', 's.id')
            ->select(
                'c.id',
                'c.id_paciente',
                'pe.apellido',
                'pe.nombre as persona_nombre',  // Alias para evitar conflictos
                'c.id_sede',
                's.nombre as sede_nombre',       // Alias para evitar conflictos
                'c.fecha_cita',
                'c.hora_cita',
                'c.estado',
                'ec.nombre as estado_nombre',    // Alias para evitar conflictos
                'c.tipo_paciente',
                'ep.nombre as tipo_paciente_nombre' // Alias para evitar conflictos
            )
            ->whereYear('c.fecha_cita', $fecha);  // Filtrar por el año de la fecha proporcionada
    
        // Si el parámetro id_sede está presente, agregar el filtro de sede
        $query->when($idSede, function($q) use ($idSede) {
            return $q->where('c.id_sede', $idSede);
        });
    
        // Ejecutar la consulta y obtener los resultados
        $citas = $query->get();
    
        return response()->json($citas, 200);
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

        try {
            // Validar los datos de la cita
            $validatedCita = $request->validate([
                'id_paciente' => 'required|exists:pacientes,id',
                //'id_quiropractico' => 'required|exists:quiropracticos,id',
                //'id_detalle_horario' => 'nullable|exists:detalle_horarios,id',
                'id_sede' => 'required|exists:sedes,id',
                'fecha_cita' => 'required|date',
                'hora_cita' => 'required|date_format:H:i',
                'estado' => 'required|integer|exists:estado_citas,id', // Pendiente, Confirmado, Atendido, Cancelado
                'tipo_paciente' => 'required|integer|exists:estado_pacientes,id', // Nuevo, Reporte, Plan, Mantenimiento
                'observaciones' => 'nullable|string|max:255',
                'id_usuario' => 'required|exists:users,id',
            ]);

            // Crear la cita
            $cita = Cita::create($validatedCita);

            DB::commit();

            return response()->json([
                'cita' => $cita,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear la cita: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cita $cita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cita $cita)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cita $cita)
    {
        //
        DB::beginTransaction();

        try {
            // Validar los datos de la cita
            $validatedCita = $request->validate([
                'id_paciente' => 'required|exists:pacientes,id',
                //'id_quiropractico' => 'required|exists:quiropracticos,id',
                //'id_detalle_horario' => 'required|exists:detalle_horarios,id',
                'id_sede' => 'required|exists:sedes,id',
                'fecha_cita' => 'required|date',
                'hora_cita' => 'required|date_format:H:i',
                'estado' => 'required|integer|exists:estado_citas,id', // Pendiente, Confirmado, Atendido, Cancelado
                'tipo_paciente' => 'required|integer|exists:estado_pacientes,id', // Nuevo, Reporte, Plan, Mantenimiento
                'observaciones' => 'nullable|string|max:255',
                'id_usuario' => 'required|exists:users,id',
            ]);

            // Actualizar la cita
            $cita->update($validatedCita);

            DB::commit();

            return response()->json([
                'cita' => $cita,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar la cita: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cita $cita)
    {
        //
        DB::beginTransaction();

        try {
            // Eliminar la cita
            $cita->delete();

            DB::commit();

            return response()->json(['message' => 'Cita eliminada correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar la cita: ' . $e->getMessage()], 500);
        }
    }
}

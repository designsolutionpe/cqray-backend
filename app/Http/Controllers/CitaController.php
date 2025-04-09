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
            Cita::with('paciente.persona','paciente.estado', 'quiropractico.persona', 'detalleHorario', 'detalleHorario.horario', 'sede','estado','tipo_paciente')->get(),
            200);
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
                'id_quiropractico' => 'required|exists:quiropracticos,id',
                'id_detalle_horario' => 'required|exists:detalle_horarios,id',
                'id_sede' => 'required|exists:sedes,id',
                'fecha_cita' => 'required|date',
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
                'id_quiropractico' => 'required|exists:quiropracticos,id',
                'id_detalle_horario' => 'required|exists:detalle_horarios,id',
                'id_sede' => 'required|exists:sedes,id',
                'fecha_cita' => 'required|date',
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

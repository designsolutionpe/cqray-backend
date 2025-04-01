<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Horario;
use App\Models\DetalleHorario;
use App\Models\Cita;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function indexHorariosPorMedico($id_doctor)
    {
        $horarios = Horario::with('detalleHorarios')
            ->where('id_doctor', $id_doctor)
            ->orderBy('dia')
            ->get();

            return response()->json($horarios,200);
    }

    public function horariosDisponibles(Request $request)
    {

        $fecha = $request->fecha;
        $id_doctor = $request->id_doctor;
        $dia = $request->dia;
        $idDetalle = $request->id_detalle_horario;

        // Buscar los horarios que no estén ocupados en la fecha proporcionada
        $horariosDisponibles = DetalleHorario::whereNotIn('id', function ($query) use ($fecha) {
            $query->select('id_detalle_horario')
                ->from('citas')
                ->where('fecha_cita', $fecha);
        })
        ->whereHas('horario', function ($query) use ($id_doctor, $dia) {
            $query->where('id_doctor', $id_doctor)
                  ->where('dia', $dia); // Filtrar por día
        });

        if ($idDetalle) {
            $horariosDisponibles->orwhere('id', $idDetalle); // Incluir el detalle horario actual
        }

        $result = $horariosDisponibles->get(['id', 'hora_inicio', 'hora_fin']);
        return response()->json($result, 200);
    }
    

    public function upsertHorarios(Request $request)
    {
        // Validar
        $validatedData = $request->validate([
            'id_doctor' => 'required|exists:doctores,id',
            'horarios' => 'required|array',
            'horarios.*.dia' => 'required|integer|min:0|max:6',
            'horarios.*.hora_inicio' => 'nullable|date_format:H:i',
            'horarios.*.hora_fin' => 'nullable|date_format:H:i',
            'horarios.*.duracion' => 'nullable|integer|min:1',
        ]);
    
        foreach ($validatedData['horarios'] as $diaData) {
            $dia = $diaData['dia'];
    
            // Si no hay datos, eliminar el horario existente
            if (empty($diaData['hora_inicio']) || empty($diaData['hora_fin']) || empty($diaData['duracion'])) {
                Horario::where('id_doctor', $validatedData['id_doctor'])
                       ->where('dia', $dia)
                       ->delete();
                continue;
            }
    
            // Crear o actualizar
            $horario = Horario::updateOrCreate(
                [
                    'id_doctor' => $validatedData['id_doctor'],
                    'dia' => $dia
                ],
                [
                    'hora_inicio' => $diaData['hora_inicio'],
                    'hora_fin' => $diaData['hora_fin'],
                    'duracion' => $diaData['duracion']
                ]
            );
    
            // Borrar bloques anteriores
            $horario->detalleHorarios()->delete();
    
            // Generar nuevos bloques
            $inicio = Carbon::createFromFormat('H:i', $diaData['hora_inicio']);
            $fin = Carbon::createFromFormat('H:i', $diaData['hora_fin']);
            $duracion = $diaData['duracion'];
    
            while ($inicio->lt($fin)) {
                $bloqueFin = (clone $inicio)->addMinutes($duracion);
                if ($bloqueFin->gt($fin)) break;
    
                $horario->detalleHorarios()->create([
                    'hora_inicio' => $inicio->format('H:i'),
                    'hora_fin' => $bloqueFin->format('H:i'),
                ]);
    
                $inicio = $bloqueFin;
            }
        }
    
        return response()->json(
            ['message' => 'Horarios actualizados o creados correctamente'], 
            201);
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
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        //
    }
}

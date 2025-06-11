<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Cita;
use Illuminate\Http\Request;
use App\Models\HistoriaClinica;
use Illuminate\Support\Facades\DB;

class HistoriaClinicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $all = HistoriaClinica::with('paciente','paciente.persona','sede','cita','paquete')->get();
        return response()->json($all,200);
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
    public function show(HistoriaClinica $historiaClinica)
    {
        //
    }

    /**
     * Obtiene todo el historial de un solo paciente
     */
    public function getHistoryByPatient(Paciente $paciente)
    {
        $ret = $paciente->historial_clinico()->get();
        return response()->json($ret,200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoriaClinica $historiaClinica)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistoriaClinica $historiaClinica)
    {
        //
    }

    /**
     * Link Cita with specified Registro Clinico
     */
    public function linkWithCita(Request $request, HistoriaClinica $historiaClinica)
    {
        DB::beginTransaction();
        try
        {
            $validated = $request->validate([
                "id_cita" => "required|integer|exists:citas,id",
            ]);

            $cita = Cita::find($validated['id_cita']);

            $historiaClinica->id_cita = $validated["id_cita"];
            $historiaClinica->save();

            $cita['id_historia_link'] = $historiaClinica->id;
            $cita->save();

            \Log::info("check link w/ cita",["id_cita"=> $validated,"id_reg"=>$historiaClinica]);

            DB::commit();
            return response()->json(["message"=>"Link completo con exito"],200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            \Log::info("ERROR LOG",["message"=>$e->getMessage()]);
            return response()->json(["message"=>"Hubo un error. Intentelo mas tarde"],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoriaClinica $historiaClinica)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Models\HistoriaClinica;

class HistoriaClinicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $all = HistoriaClinica::with('paciente','sede','cita')->get();
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
     * Remove the specified resource from storage.
     */
    public function destroy(HistoriaClinica $historiaClinica)
    {
        //
    }
}

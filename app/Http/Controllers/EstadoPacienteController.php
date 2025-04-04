<?php

namespace App\Http\Controllers;

use App\Models\EstadoPaciente;
use Illuminate\Http\Request;

class EstadoPacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(EstadoPaciente::all(),200);
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
    public function show(EstadoPaciente $estadoPaciente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EstadoPaciente $estadoPaciente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EstadoPaciente $estadoPaciente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstadoPaciente $estadoPaciente)
    {
        //
    }
}

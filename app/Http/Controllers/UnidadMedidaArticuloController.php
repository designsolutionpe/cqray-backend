<?php

namespace App\Http\Controllers;

use App\Models\UnidadMedidaArticulo;
use Illuminate\Http\Request;

class UnidadMedidaArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $all = UnidadMedidaArticulo::all();
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
    public function show(UnidadMedidaArticulo $unidadMedidaArticulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnidadMedidaArticulo $unidadMedidaArticulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnidadMedidaArticulo $unidadMedidaArticulo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnidadMedidaArticulo $unidadMedidaArticulo)
    {
        //
    }
}

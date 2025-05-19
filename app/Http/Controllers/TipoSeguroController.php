<?php

namespace App\Http\Controllers;

use App\Models\TipoSeguro;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TipoSeguroController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $items = TipoSeguro::all();
        return $this->successResponse($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'nombre' => 'required|string',
            'tipo' => 'required|string|in:SNP,AFP',
            'snp' => 'nullable|numeric',
            'aporte' => 'nullable|numeric',
            'invalidez' => 'nullable|numeric',
            'comision' => 'nullable|numeric'
        ];
        $this->validate($request,$rules);

        $tipo = TipoSeguro::create($request->all());
        return $this->successResponse($tipo,Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoSeguro $tipoSeguro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoSeguro $tipoSeguro)
    {
        //
        DB::beginTransaction();
        try
        {
            $rules = [
                'nombre' => 'required|string',
                'tipo' => 'required|string|in:SNP,AFP',
                'snp' => 'nullable|numeric',
                'aporte' => 'nullable|numeric',
                'invalidez' => 'nullable|numeric',
                'comision' => 'nullable|numeric'
            ];
            $this->validate($request,$rules);
    
            $tipoSeguro->update($request->all());
            DB::commit();

            return $this->successResponse($tipoSeguro);
        }
        catch(\Exception $exception)
        {
            DB::rollback();
            return $this->errorResponse('Error al actualizar tipo seguro. Log: ' . $exception->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoSeguro $tipoSeguro)
    {
        //
        DB::beginTransaction();
        try
        {
            $tipoSeguro->delete();
            DB::commit();
            return $this->successResponse(true);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return $this->errorResponse('Error al eliminar seguro'.$e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

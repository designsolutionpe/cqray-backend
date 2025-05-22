<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Persona;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $items = Empleado::with(['persona','tipo_seguro','sede']);
        if($request->filled('sede'))
            $items = $items->where("id_sede",$request->query("sede"));
        return $this->successResponse($items->get());
    }

    public function indexBySede(Request $request)
    {
        $items = Empleado::with(["persona","tipo_seguro"]);
        $items->where("id_sede",$request->query("sede"));
        return $this->successResponse($items->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
        $rules = [
            // Datos persona
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'tipo_documento' => 'required|string|in:' . implode(',',Persona::tipoDocumentos()),
            'numero_documento' => 'required|string|max:20|unique:personas,numero_documento',
            'genero' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
            'email' => 'nullable|string',
            'id_sede' => 'required|integer|exists:sedes,id',

            // Datos empleado
            'sueldo' => 'required|numeric|min:0',
            'id_tipo_seguro' => 'required|integer|exists:tipo_seguros,id',
            'is_planilla' => 'required|boolean'
        ];

        $this->validate($request,$rules);

        $persona = Persona::create($request->all());

        $empleado_data = array_merge($request->all(),["id_persona"=>$persona->id]);

        $empleado = Empleado::create($empleado_data);

        DB::commit();

        return $this->successResponse($empleado);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return $this->errorResponse("No se pudo crear empleado. Error: " . $e->getMessage(),422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleado $empleado)
    {
        //
        DB::beginTransaction();
        try{
           $rules = [
               // Datos persona
               'nombre' => 'required|string|max:255',
               'apellido' => 'required|string|max:255',
               'tipo_documento' => 'required|string|in:' . implode(',',Persona::tipoDocumentos()),
               'numero_documento' => 'required|string|max:20|unique:personas,numero_documento',
               'genero' => 'nullable|string',
               'fecha_nacimiento' => 'nullable|date',
               'telefono' => 'nullable|string',
               'direccion' => 'nullable|string',
               'email' => 'nullable|string',
               // Datos empleado
               'sueldo' => 'required|numeric|min:0',
               'id_tipo_seguro' => 'required|integer|exists:tipo_seguros,id',
               'is_planilla' => 'required|boolean'
           ];

           $this->validate($request,$rules);

           $empleado->update($request->all());

           DB::commit();

           return $this->successResponse($empleado);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return $this->errorResponse("Error al actualizar empleado");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado)
    {
        DB::beginTransaction();
        try
        {
            $empleado->delete();
            DB::commit();

            return $this->successResponse(true);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return $this->errorResponse("No se pudo eliminar el empleado",500);
        }
    }
}

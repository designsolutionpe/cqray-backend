<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Sede;
use App\Models\User;
use App\Models\Articulo;
use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Models\DetalleHorario;
use App\Models\EstadoPaciente;
use App\Models\HistoriaClinica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $items = Cita::with([
            'paciente.persona',
            'paciente.estado', 
            'quiropractico.persona', 
            'sede',
            'estado',
            'tipo_paciente',
            'usuario',
            'usuario.sede'
        ]);

        if($request->filled('sede'))
            $items->where('id_sede',$request->query('sede'));

        if($request->filled('paciente'))
            $items->where('id_paciente',$request->query('paciente'));

        $result = $items->orderBy('fecha_cita','desc')->get();

        return response()->json($result,200);
    }

    public function count(Request $request)
    {
      $sede = $request->query("id_sede");
      if(isset($sede))
        return response()->json(Cita::where("id_sede",$sede)->get()->count(),200);
      else
        return response()->json(Cita::all()->count(),200);
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
                'id_sede' => 'required|exists:sedes,id',
                'fecha_cita' => 'required|date',
                'hora_cita' => 'required|date_format:H:i',
                'estado' => 'required|integer|exists:estado_citas,id', // Pendiente, Confirmado, Atendido, Cancelado
                'tipo_paciente' => 'required|integer|exists:estado_pacientes,id', // Nuevo, Reporte, Plan, Mantenimiento
                'observaciones' => 'nullable|string|max:255',
                'id_usuario' => 'required|exists:users,id',
                'id_paquete' => 'nullable|integer|exists:articulos,id'
            ]);

            \Log::info('check cita',['data'=>$validatedCita]);

            // Crear la cita
            $cita = Cita::create($validatedCita);

            \Log::info('cita',['check'=>$cita]);

            // Enlaza cita con sesion adquirida pendiente por paciente
            if( isset($validatedCita['id_paquete']) && $validatedCita['id_paquete'] != null )
            {
                $sesion = HistoriaClinica::where([
                    'id_articulo' => $validatedCita['id_paquete'],
                    'id_paciente' => $validatedCita['id_paciente'],
                    'ended_at' => null,
                    'activo' => 1,
                ])->get()->first();

                \Log::info('sesion',['data'=>$sesion]);

                if($sesion != null)
                {
                    \Log::info("fhecking sesion",["aa"=>$sesion]);
                    $sesion['id_cita'] = $cita->id;
    
                    $sesion->save();
                }

                // Cambiar el estado del paciente

                // Cambia el estado del paciente dependiendo del paquete
                // al que se le esta atribuyendo la cita
                $paciente = Paciente::find($validatedCita['id_paciente']);
                $articulo = Articulo::find($validatedCita['id_paquete']);
                $estados = EstadoPaciente::all();

                $estado_articulo = $estados->filter( fn($s) => $s->id == $articulo->id_estado_paciente )->first();

                \Log::info('estado articulo',['estado'=>$estado_articulo->id,'estados'=>$estados]);

                $cita['tipo_paciente'] = $estado_articulo->id;
                $paciente['estado'] = $estado_articulo->id;

                $cita->save();
                $paciente->save();
            }

            if( $validatedCita['estado'] == 3 )
            {
                // Verifica si el paquete actual se desactiva o no
                $historias = HistoriaClinica::with(['cita'])->where([
                    'id_paciente' => $validatedCita['id_paciente'],
                    'activo' => 1
                ])->get();

                // Obtener paquetes no activas (si existen) para activarlas
                // en caso las actuales seran desactivadas
                $historias_no_activas = HistoriaClinica::with(['paquete'])->where([
                    'id_paciente' => $validatedCita['id_paciente'],
                    'activo' => 0,
                    'ended_at' => null
                ])->get();
                
                if( $historias->count() > 0 )
                {
                    \Log::info('Hay historias activas - crear cita',['historias'=>$historias]);
                    // Obtiene el paquete asociado
                    $articulo = Articulo::find($historias[0]['id_articulo']);

                    // Filtra las sesiones que ya han sido atendidas
                    $n_atendidos = $historias->filter(fn($h) => $h['cita'] != null ? $h['cita']['estado'] == 3 : false)->count();

                    \Log::info('Check cantidad = atendidos',['cantidad'=>$articulo->cantidad,'atendidos'=>$n_atendidos,'historias'=>$historias->filter(fn($h) => $h['cita'] != null ? $h['cita']['estado'] == 3 : false)]);
                    // Si todas las sesiones han sido atendidas, cambiar el estado
                    // del paquete actual a inactivo y activar el siguiente paquete
                    // si existe
                    if( $articulo->cantidad == $n_atendidos )
                    {
                        // Desactiva las sesiones del paquete actual
                        foreach($historias as &$historia)
                        {
                            $historia['activo'] = 0;
                            $historia->ended_at = now();
                            $historia->save();
                        }

                        \Log::info('Check si hay historias no activas',['historias'=>$historias_no_activas->count()]);
                        // Activa las sesiones del siguiente paquete disponible
                        if( $historias_no_activas->count() > 0)
                        {
                            // Obtiene un array con los uuid para filtrar el siguiente
                            // paquete disponible
                            $result = $historias_no_activas->keyBy('uuid')->values();

                            
                            // Activa el siguiente paquete
                            $uuid_siguiente = $result[0]['uuid'];
                            $historias_por_activar = $historias_no_activas->filter(fn($h)=>$h['uuid'] == $uuid_siguiente && $h['ended_at'] == null);
                            \Log::info('check historias por activas',['historias'=>$historias_por_activar]);
                            foreach($historias_por_activar as &$historia)
                            {
                                $historia['activo'] = 1;
                                $historia->save();
                            }
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'cita' => $cita,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info("CITA STORE ERROR",["backtrace"=>$e->getMessage()]);
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

            // Verifica si el paquete actual se desactiva o no
            $historias = HistoriaClinica::with(['cita'])->where([
                'id_paciente' => $validatedCita['id_paciente'],
                'activo' => 1
            ])->get();

            // Obtener paquetes no activas (si existen) para activarlas
            // en caso las actuales seran desactivadas
            $historias_no_activas = HistoriaClinica::with(['paquete'])->where([
                'id_paciente' => $validatedCita['id_paciente'],
                'activo' => 0,
                'ended_at' => null
            ])->get();

            
            if( $historias->count() > 0 )
            {
                if( $cita->estado == 4 )
                {
                    $historia_asociada = $historias->firstWhere('id_cita',$cita->id);
                    $historia_asociada->id_cita = null;
                    $historia_asociada->save();
                }

                \Log::info('Hay historias activas',['historias'=>$historias]);
                // Obtiene el paquete asociado
                $articulo = Articulo::find($historias[0]['id_articulo']);

                // Filtra las sesiones que ya han sido atendidas
                    $n_atendidos = $historias->filter(fn($h) => $h['cita'] != null ? $h['cita']['estado'] == 3 : false)->count();

                // \Log::info('Check cantidad = atendidos',['cantidad'=>$articulo->cantidad,'atendidos'=>$n_atendidos,'historias'=>$historias->filter(fn($h)=>$h['cita']['estado'] == 3)]);
                // Si todas las sesiones han sido atendidas, cambiar el estado
                // del paquete actual a inactivo y activar el siguiente paquete
                // si existe
                if( $articulo->cantidad == $n_atendidos )
                {
                    // Desactiva las sesiones del paquete actual
                    foreach($historias as &$historia)
                    {
                        $historia['activo'] = 0;
                        $historia->ended_at = now();
                        $historia->save();
                    }

                    \Log::info('Check si hay historias no activas',['historias'=>$historias_no_activas->count()]);
                    // Activa las sesiones del siguiente paquete disponible
                    if( $historias_no_activas->count() > 0)
                    {
                        // Obtiene un array con los uuid para filtrar el siguiente
                        // paquete disponible
                        $result = $historias_no_activas->keyBy('uuid')->values();

                        
                        // Activa el siguiente paquete
                        $uuid_siguiente = $result[0]['uuid'];
                        $historias_por_activar = $historias_no_activas->filter(fn($h)=>$h['uuid'] == $uuid_siguiente&& $h["ended_at"]==null);
                        \Log::info('check historias por activas',['historias'=>$historias_por_activar]);
                        foreach($historias_por_activar as &$historia)
                        {
                            $historia['activo'] = 1;
                            $historia->save();
                        }
                    }
                }
            }

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

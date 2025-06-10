<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\CajaChicaController;
use App\Http\Controllers\EstadoCitaController;
use App\Http\Controllers\TipoSeguroController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\NotaCreditoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\QuiropracticoController;
use App\Http\Controllers\EstadoPacienteController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\CategoriaArticuloController;
use App\Http\Controllers\UnidadMedidaArticuloController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\EstadisticasController;


//Route::get('/sedes', [SedeController::class, 'index']);
// Route::middleware('auth:sanctum')->get('/sedes', [SedeController::class, 'index']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/images/{path}/{image}',function($path,$image){
  \Log::info('check image path',['path'=>$path,'image'=>$image]);
  $path = public_path("storage/$path/$image");
  if(file_exists($path))
    return response()->file($path);
  return response()->json(['error'=>'Image not found'], 404);
});

Route::middleware(['auth:sanctum'])->group(function () {
  
  // Gestion de Sedes
  Route::get('/sedes', [SedeController::class, 'index']);
  Route::get('/sedes/count',[SedeController::class,'count']);
  
  // Gestion de citas
  Route::get('/citas/estados',[EstadoCitaController::class,'index']);
  Route::get('/citas', [CitaController::class, 'index']);
  Route::get('/citas-paciente',[CitaController::class,'getCitasForSelect']);
  Route::get('/citas/count',[CitaController::class,'count']);
  Route::post('/citas', [CitaController::class, 'store']);
  Route::put('/citas/{cita}', [CitaController::class, 'update']);
  Route::delete('/citas/{cita}', [CitaController::class, 'destroy']);

  Route::get('/citas/fechas', [CitaController::class, 'indexByFechaYSede']);

  // Personas
  Route::get('/personas', [PersonaController::class, 'index']);
  Route::post('/personas', [PersonaController::class, 'store']);
  Route::put('/personas/{persona}', [PersonaController::class, 'update']);
  Route::delete('/personas/{persona}', [PersonaController::class, 'destroy']);
  
  // Gestion de pacientes
  Route::get('/pacientes/estados',[EstadoPacienteController::class,'index']);
  Route::get('/pacientes', [PacienteController::class, 'index']);
  Route::get('/pacientes/count',[PacienteController::class,'count']);
  Route::get('/pacientes/{paciente}',[PacienteController::class,'show']);
  
  // Gestion de Historial Clinico
  Route::get('/historias-clinicas',[HistoriaClinicaController::class,'index']);
  Route::get('/pacientes/{paciente}/historial-clinico',[HistoriaClinicaController::class,'getHistoryByPatient']);
  Route::put('/historias-clinicas/{historiaClinica}/link',[HistoriaClinicaController::class,'linkWithCita']);

  // Gestion de horarios
  Route::get('/horarios/disponibles', [HorarioController::class, 'horariosDisponibles']);

  // Gestion de Articulos
  Route::get('/articulos',[ArticuloController::class,'index']);
  Route::post('/articulos',[ArticuloController::class,'store']);
  Route::put('/articulos/{articulo}',[ArticuloController::class,'update']);
  Route::delete('/articulos/{articulo}',[ArticuloController::class,'destroy']);
  Route::get('articulos/buscar', [ArticuloController::class, 'buscar']);

  // Gestion de Categorias Articulos
  Route::get('/articulos/categorias',[CategoriaArticuloController::class,'index']);
  Route::post('/articulos/categorias',[CategoriaArticuloController::class,'store']);
  Route::put('/articulos/categorias/{categoriaArticulo}',[CategoriaArticuloController::class,'update']);
  Route::delete('/articulos/categorias/{categoriaArticulo}',[CategoriaArticuloController::class,'destroy']);

  // Gestion de Unidad de Medida
  Route::get('/articulos/medidas',[UnidadMedidaArticuloController::class,'index']);
  Route::post('/articulos/medidas',[UnidadMedidaArticuloController::class,'store']);
  Route::put('/articulos/medidas/{medida}',[UnidadMedidaArticuloController::class,'update']);
  Route::delete('/articulos/medidas/{medida}',[UnidadMedidaArticuloController::class,'destroy']);

  // Configuracion
  Route::get('/configuracion', [ConfiguracionController::class, 'index']);
  Route::put('/configuracion/{configuracion}', [ConfiguracionController::class, 'update']);

  // Comprobantes
  Route::get('/comprobantes', [ComprobanteController::class, 'index']);
  Route::get('/comprobantes/count',[ComprobanteController::class,'count']);
  Route::post('/comprobantes', [ComprobanteController::class, 'store']);
  Route::put('/comprobantes/{comprobante}', [ComprobanteController::class, 'update']);
  Route::delete('/comprobantes/{comprobante}', [ComprobanteController::class, 'destroy']);
  Route::get('/comprobantes/buscar', [ComprobanteController::class, 'searchComprobantes']);
  Route::get('/comprobantes/last',[ComprobanteController::class,'getLastItem']);
  Route::get('/comprobantes/deuda/{persona}',[ComprobanteController::class,'verificationPersonDebt']);

  Route::get('/pacientes/{persona}/comprobantes',[PersonaController::class,'getVouchers']);
  // Notas de Crédito
  Route::get('/notas-creditos', [NotaCreditoController::class, 'index']);
  Route::post('/notas-creditos', [NotaCreditoController::class, 'store']);
  Route::put('/notas-creditos/{notaCredito}', [NotaCreditoController::class, 'update']);
  Route::delete('/notas-creditos/{notaCredito}', [NotaCreditoController::class, 'destroy']);
  Route::get('/notas-creditos/last',[NotaCreditoController::class,'getLastItem']);

  // Tipos de Pagos
  Route::get('/tipos-pago',[TipoPagoController::class,'index']);
  Route::post('/tipos-pago',[TipoPagoController::class,'store']);
  Route::put('/tipos-pago/{tipoPago}',[TipoPagoController::class,'update']);
  Route::delete('/tipos-pago/{tipoPago}',[TipoPagoController::class,'destroy']);

  // Roles
  Route::get('/roles',[RoleController::class,'index']);
  Route::post('/roles',[RoleController::class,'store']);
  Route::put('/roles/{role}',[RoleController::class,'update']);
  Route::delete('/roles/{role}',[RoleController::class,'destroy']);

  // Usuarios
  Route::get('/usuarios', [UserController::class, 'index']);
  Route::get('/usuarios/{id}', [UserController::class, 'show']);
  Route::post('/usuarios', [UserController::class, 'store']);
  Route::put('/usuarios/{user}', [UserController::class, 'update']);
  Route::delete('/usuarios/{user}', [UserController::class, 'destroy']);
  Route::put('/usuario-persona/{user}', [UserController::class, 'updateUserAndPersona']);

  // Caja Chica
  Route::get('/cajachica',[CajaChicaController::class,'index']);
  Route::post('/cajachica',[CajaChicaController::class,'store']);
  Route::put('/cajachica/{cajaChica}',[CajaChicaController::class,'update']);
  Route::delete('/cajachica/{cajaChica}',[CajaChicaController::class,'delete']);

  // Tipo seguro
  Route::controller(TipoSeguroController::class)->group(function () {
      Route::get('/tipos-seguro', 'index');
      Route::post('/tipos-seguro', 'store');
      Route::put('/tipos-seguro/{tipoSeguro}','update');
      Route::delete('/tipos-seguro/{tipoSeguro}','destroy');
  });

  Route::controller(EmpleadoController::class)->group(function(){
    Route::get('/empleados','index');
    Route::post('/empleados','store');
    Route::put('/empleados/{empleado}','update');
    Route::delete('/empleados/{empleado}','destroy');
  });

  Route::get('/statistics/dashboard',[EstadisticasController::class,'dashboard']);

});

Route::post('/sedes', [SedeController::class, 'store']);
Route::put('/sedes/{sede}', [SedeController::class, 'update']);
Route::delete('/sedes/{sede}', [SedeController::class, 'destroy']);

Route::post('/pacientes', [PacienteController::class, 'store']);
Route::put('/pacientes/{paciente}', [PacienteController::class, 'update']);
Route::delete('/pacientes/{paciente}', [PacienteController::class, 'destroy']);

Route::get('/quiropracticos', [QuiropracticoController::class, 'index']);
Route::post('/quiropracticos', [QuiropracticoController::class, 'store']);
Route::put('/quiropracticos/{quiropractico}', [QuiropracticoController::class, 'update']);
Route::delete('/quiropracticos/{quiropractico}', [QuiropracticoController::class, 'destroy']);

Route::get('/personas/buscar', [PersonaController::class, 'searchPersonas']);

Route::post('/horarios/upsert', [HorarioController::class, 'upsertHorarios']);
Route::get('/horarios/quiropracticos/{id_quiropractico}', [HorarioController::class, 'indexHorariosPorMedico']);


Route::get('/pagos', [PagoController::class, 'index']);
Route::post('/pagos', [PagoController::class, 'store']);
Route::put('/pagos/{pago}', [PagoController::class, 'update']);
Route::put('/pagos/{pago}/estado', [PagoController::class, 'cambiarEstado']);
Route::delete('/pagos/{pago}', [PagoController::class, 'destroy']);
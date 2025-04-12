<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\EstadoCitaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\QuiropracticoController;
use App\Http\Controllers\EstadoPacienteController;
use App\Http\Controllers\CategoriaArticuloController;


//Route::get('/sedes', [SedeController::class, 'index']);
// Route::middleware('auth:sanctum')->get('/sedes', [SedeController::class, 'index']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
  
  // Gestion de Sedes
  Route::get('/sedes', [SedeController::class, 'index']);
  
  // Gestion de citas
  Route::get('/citas/estados',[EstadoCitaController::class,'index']);
  Route::get('/citas', [CitaController::class, 'index']);
  Route::post('/citas', [CitaController::class, 'store']);
  Route::put('/citas/{cita}', [CitaController::class, 'update']);
  Route::delete('/citas/{cita}', [CitaController::class, 'destroy']);

  Route::get('/citas/fechas', [CitaController::class, 'indexByFechaYSede']);

  
  // Gestion de pacientes
  Route::get('/pacientes/estados',[EstadoPacienteController::class,'index']);
  Route::get('/pacientes', [PacienteController::class, 'index']);
  Route::get('/pacientes/{paciente}',[PacienteController::class,'show']);
  
  // Gestion de horarios
  Route::get('/horarios/disponibles', [HorarioController::class, 'horariosDisponibles']);

  // Gestion de Categorias Articulos
  Route::get('/articulos/categorias',[CategoriaArticuloController::class,'index']);
  Route::post('/articulos/categorias',[CategoriaArticuloController::class,'store']);
  Route::put('/articulos/categorias/{categoriaArticulo}',[CategoriaArticuloController::class,'update']);
  Route::delete('/articulos/categorias/{categoriaArticulo}',[CategoriaArticuloController::class,'destroy']);

  // Configuracion
  Route::get('/configuracion', [ConfiguracionController::class, 'index']);
  Route::put('/configuracion/{configuracion}', [ConfiguracionController::class, 'update']);
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

Route::get('/usuarios', [UserController::class, 'index']);
Route::get('/usuarios/{id}', [UserController::class, 'show']);
Route::post('/usuarios', [UserController::class, 'store']);
Route::put('/usuarios/{usuario}', [UserController::class, 'update']);
Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy']);
Route::put('/usuario-persona/{user}', [UserController::class, 'updateUserAndPersona']);


Route::get('/personas/buscar', [PersonaController::class, 'searchPersonas']);

Route::post('/horarios/upsert', [HorarioController::class, 'upsertHorarios']);
Route::get('/horarios/quiropracticos/{id_quiropractico}', [HorarioController::class, 'indexHorariosPorMedico']);


Route::get('/pagos', [PagoController::class, 'index']);
Route::post('/pagos', [PagoController::class, 'store']);
Route::put('/pagos/{pago}', [PagoController::class, 'update']);
Route::put('/pagos/{pago}/estado', [PagoController::class, 'cambiarEstado']);
Route::delete('/pagos/{pago}', [PagoController::class, 'destroy']);
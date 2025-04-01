<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\CitaController;
use Illuminate\Support\Facades\Route;


//Route::get('/sedes', [SedeController::class, 'index']);
Route::middleware('auth:sanctum')->get('/sedes', [SedeController::class, 'index']);

Route::post('/sedes', [SedeController::class, 'store']);
Route::put('/sedes/{sede}', [SedeController::class, 'update']);
Route::delete('/sedes/{sede}', [SedeController::class, 'destroy']);

Route::get('/pacientes', [PacienteController::class, 'index']);
Route::post('/pacientes', [PacienteController::class, 'store']);
Route::put('/pacientes/{paciente}', [PacienteController::class, 'update']);
Route::delete('/pacientes/{paciente}', [PacienteController::class, 'destroy']);

Route::get('/doctores', [DoctorController::class, 'index']);
Route::post('/doctores', [DoctorController::class, 'store']);
Route::put('/doctores/{doctor}', [DoctorController::class, 'update']);
Route::delete('/doctores/{doctor}', [DoctorController::class, 'destroy']);

Route::get('/usuarios', [UserController::class, 'index']);
Route::post('/usuarios', [UserController::class, 'store']);
Route::put('/usuarios/{usuario}', [UserController::class, 'update']);
Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/personas/buscar', [PersonaController::class, 'searchPersonas']);

Route::get('/horarios/disponibles', [HorarioController::class, 'horariosDisponibles']);
Route::post('/horarios/upsert', [HorarioController::class, 'upsertHorarios']);
Route::get('/horarios/doctores/{id_doctor}', [HorarioController::class, 'indexHorariosPorMedico']);

Route::get('/citas', [CitaController::class, 'index']);
Route::post('/citas', [CitaController::class, 'store']);
Route::put('/citas/{cita}', [CitaController::class, 'update']);
Route::delete('/citas/{cita}', [CitaController::class, 'destroy']);
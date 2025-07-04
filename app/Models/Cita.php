<?php

namespace App\Models;

use App\Models\Sede;
use App\Models\Paciente;
use App\Models\Quiropractico;
use App\Models\DetalleHorario;
use App\Models\EstadoPaciente;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cita extends Model
{
    /** @use HasFactory<\Database\Factories\CitaFactory> */
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'id_paciente', 
        //'id_quiropractico', 
        //'id_detalle_horario', 
        'id_sede', 
        'fecha_cita', 
        'hora_cita',
        'estado', 
        'tipo_paciente', 
        'fecha_atencion', 
        'hora_atencion', 
        'observaciones',
        'id_usuario'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    public function quiropractico()
    {
        return $this->belongsTo(Quiropractico::class, 'id_quiropractico');
    }

    public function detalleHorario()
    {
        return $this->belongsTo(DetalleHorario::class, 'id_detalle_horario');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'id_sede');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoCita::class,'estado');
    }

    public function tipo_paciente()
    {
        return $this->belongsTo(EstadoPaciente::class,'tipo_paciente');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Sede;
use App\Models\DetalleHorario;

class Cita extends Model
{
    /** @use HasFactory<\Database\Factories\CitaFactory> */
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'id_paciente', 
        'id_doctor', 
        'id_detalle_horario', 
        'id_sede', 
        'fecha_cita', 
        'estado', 
        'tipo_paciente', 
        'fecha_atencion', 
        'hora_atencion', 
        'observaciones'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id_doctor');
    }

    public function detalleHorario()
    {
        return $this->belongsTo(DetalleHorario::class, 'id_detalle_horario');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'id_sede');
    }

}

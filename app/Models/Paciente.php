<?php

namespace App\Models;

use App\Models\Sede;
use App\Models\Persona;
use App\Models\HistoriaClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paciente extends Model
{
    /** @use HasFactory<\Database\Factories\PacienteFactory> */
    use HasFactory;

    protected $fillable = [
        'id_persona', 
        'id_sede', 
        'historia_clinica',
        'estado'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'id_sede');
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_paciente');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPaciente::class,'estado');
    }

    public function historial_clinico()
    {
        return $this->hasMany(HistoriaClinica::class,'id_paciente');
    }

    /*
    public function historias()
    {
        return $this->hasMany(HistoriaClinica::class, 'id_paciente');
    }
    */
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;
use App\Models\Sede;

class Paciente extends Model
{
    /** @use HasFactory<\Database\Factories\PacienteFactory> */
    use HasFactory;

    protected $fillable = [
        'id_persona', 
        'id_sede', 
        'grupo_sanguineo',
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

    /*
    public function historias()
    {
        return $this->hasMany(HistoriaClinica::class, 'id_paciente');
    }
    */
}

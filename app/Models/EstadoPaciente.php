<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPaciente extends Model
{
    /** @use HasFactory<\Database\Factories\EstadoPacienteFactory> */
    use HasFactory;

    protected $table = 'estado_pacientes';

    protected $fillable = [
        'nombre'
    ];
}

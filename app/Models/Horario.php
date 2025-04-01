<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleHorario;

class Horario extends Model
{
    /** @use HasFactory<\Database\Factories\HorarioFactory> */
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'id_doctor',
        'dia',
        'hora_inicio',
        'hora_fin',
        'duracion',
    ];

    public function detalleHorarios()
    {
        return $this->hasMany(DetalleHorario::class,'id_horario');
    }
}

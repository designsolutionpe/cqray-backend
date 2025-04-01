<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Horario;

class DetalleHorario extends Model
{
    /** @use HasFactory<\Database\Factories\DetalleHorarioFactory> */
    use HasFactory;

    protected $table = 'detalle_horarios';

    protected $fillable = [
        'id_horario',
        'hora_inicio',
        'hora_fin',
    ];

    public function horario()
    {
        return $this->belongsTo(Horario::class,'id_horario');
    }
    
}

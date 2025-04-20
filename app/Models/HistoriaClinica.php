<?php

namespace App\Models;

use App\Models\Articulo;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistoriaClinica extends Model
{
    /** @use HasFactory<\Database\Factories\HistoriaClinicaFactory> */
    use HasFactory;

    protected $table = 'historia_clinicas';

    protected $fillable = [
        'id_paciente',
        'id_sede',
        'id_estado_cita',
        'id_articulo',
        'estado_pago',
        'activo'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class,'id_paciente');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class,'id_sede');
    }

    public function estado_cita()
    {
        return $this->belongsTo(EstadoCita::class,'id_estado_cita');
    }

    public function paquete()
    {
        return $this->belongsTo(Articulo::class,'id_articulo');
    }
}

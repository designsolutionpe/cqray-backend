<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;
use App\Models\Sede;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory;

    protected $table = 'doctores';

    protected $fillable = [
        'id_persona', 
        'id_sede', 
        'numero_colegiatura',
        'especialidad',
        'datos_contacto',
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

}

<?php

namespace App\Models;

use App\Models\Persona;
use App\Models\TipoSeguro;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    //
    protected $table = 'empleados';

    protected $fillable = [
        'id_persona',
        'id_tipo_seguro',
        'sueldo',
        'is_planilla',
        'is_active'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class,'id_persona');
    }

    public function tipo_seguro()
    {
        return $this->belongsTo(TipoSeguro::class,'id_tipo_seguro');
    }
}

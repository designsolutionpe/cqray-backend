<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoSeguro extends Model
{
    //
    protected $table = 'tipo_seguros';

    protected $fillable = [
        'nombre',
        'tipo',
        'snp',
        'aporte',
        'invalidez',
        'comision'
    ];
}

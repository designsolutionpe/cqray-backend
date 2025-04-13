<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadMedidaArticulo extends Model
{
    /** @use HasFactory<\Database\Factories\UnidadMedidaArticuloFactory> */
    use HasFactory;

    protected $table = 'unidad_medida_articulos';

    protected $fillable = [
        'nombre'
    ];
}

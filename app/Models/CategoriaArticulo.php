<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaArticulo extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaArticuloFactory> */
    use HasFactory;

    protected $table = "categoria_articulos";

    protected $fillable = [
        'nombre',
        'estado'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    /** @use HasFactory<\Database\Factories\ConfiguracionFactory> */
    use HasFactory;
    protected $table = 'configuraciones';
    protected $fillable = [
        'nombre',
        'ruc',
        'numero_sucursales',
        'imagen1',
        'imagen2',
        'estado'
    ];
}

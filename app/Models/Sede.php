<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    /** @use HasFactory<\Database\Factories\SedeFactory> */
    use HasFactory;

    protected $table = 'sedes';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'foto'
    ];

    public $timestamps = true; // Para created_at y updated_at
}

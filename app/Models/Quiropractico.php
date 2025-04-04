<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiropractico extends Model
{
    /** @use HasFactory<\Database\Factories\QuiropracticoFactory> */
    use HasFactory;

    protected $table = 'quiropracticos';

    protected $fillable = [
        'id_persona', 
        'id_sede', 
        'numero_colegiatura',
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

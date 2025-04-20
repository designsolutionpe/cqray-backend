<?php

namespace App\Models;

use App\Models\User;
use App\Models\Paciente;
use App\Models\Comprobante;
use App\Models\Quiropractico;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Persona extends Model
{
    /** @use HasFactory<\Database\Factories\PersonaFactory> */
    use HasFactory;

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombre',
        'apellido',
        'genero',
        'fecha_nacimiento',
        'direccion',
        'telefono',
        'email',
        'foto',
    ];

    public static function tipoDocumentos()
    {
        return ['DNI', 'Carnet de Extranjería', 'Pasaporte', 'Otro'];
    }

    public static function generos()
    {
        return ['Masculino', 'Femenino'];
    }

    // Relación con Paciente
    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'id_persona');
    }

    // Relación con Quiropractico
    public function quiropractico()
    {
        return $this->hasOne(Quiropractico::class, 'id_persona');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_persona');
    }

    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class,'id_persona');
    }
}

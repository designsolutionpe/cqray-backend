<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\User;

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

    // Relación con Doctor
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id_persona');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_persona');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $persona1 = Persona::create([
            'tipo_documento' => 'DNI',
            'numero_documento' => '02345678',
            'nombre' => 'Miguel',
            'apellido' => 'García',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-05-10',
            'direccion' => 'Calle Ficticia 123',
            'telefono' => '987654321',
            'email' => 'miguel.garcia@example.com',
        ]);

        $persona2 = Persona::create([
            'tipo_documento' => 'DNI',
            'numero_documento' => '23456789',
            'nombre' => 'Ana',
            'apellido' => 'Gómez',
            'genero' => 'Femenino',
            'fecha_nacimiento' => '1985-08-15',
            'direccion' => 'Avenida Principal 456',
            'telefono' => '987654322',
            'email' => 'ana.gomez@example.com',
        ]);

        $persona3 = Persona::create([
            'tipo_documento' => 'DNI',
            'numero_documento' => '34567890',
            'nombre' => 'Carlos',
            'apellido' => 'Sánchez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1992-03-20',
            'direccion' => 'Boulevard Central 789',
            'telefono' => '987654323',
            'email' => 'carlos.sanchez@example.com',
        ]);

        $persona4 = Persona::create([
            'tipo_documento' => 'DNI',
            'numero_documento' => '45678901',
            'nombre' => 'Laura',
            'apellido' => 'Martínez',
            'genero' => 'Femenino',
            'fecha_nacimiento' => '1988-11-05',
            'direccion' => 'Calle Secundaria 321',
            'telefono' => '987654324',
            'email' => 'laura.martinez@example.com',
        ]);

        $persona5 = Persona::create([
            'tipo_documento' => 'DNI',
            'numero_documento' => '56789012',
            'nombre' => 'Pedro',
            'apellido' => 'López',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1995-01-25',
            'direccion' => 'Avenida de las Américas 654',
            'telefono' => '987654325',
            'email' => 'pedro.lopez@example.com',
        ]);

        // Crear los usuarios correspondientes a cada persona
        User::create([
            'login' => 'mgarcia',
            'email' => 'miguel.garcia@example.com',
            'password' => Hash::make('123456'),
            'rol' => 'Administrador',
            'id_persona' => $persona1->id,
            'id_sede' => 1,
        ]);

        User::create([
            'login' => 'agomez',
            'email' => 'ana.gomez@example.com',
            'password' => Hash::make('123456'),
            'rol' => 'Administrador',
            'id_persona' => $persona2->id,
            'id_sede' => 2,
        ]);

        User::create([
            'login' => 'csanchez',
            'email' => 'carlos.sanchez@example.com',
            'password' => Hash::make('123456'),
            'rol' => 'Administrador',
            'id_persona' => $persona3->id,
            'id_sede' => 3,
        ]);

        User::create([
            'login' => 'lmartinez',
            'email' => 'laura.martinez@example.com',
            'password' => Hash::make('123456'),
            'rol' => 'Administrador',
            'id_persona' => $persona4->id,
            'id_sede' => 4,
        ]);

        User::create([
            'login' => 'plopez',
            'email' => 'pedro.lopez@example.com',
            'password' => Hash::make('123456'),
            'rol' => 'Administrador',
            'id_persona' => $persona5->id,
            'id_sede' => 5,
        ]);
    }
}

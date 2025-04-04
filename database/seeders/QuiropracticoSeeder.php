<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\Quiropractico;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuiropracticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Crear una persona para el quiropractico
            $persona = Persona::create([
                'tipo_documento' => 'DNI',
                'numero_documento' => '00000001',
                'nombre' => 'Jorge Mauricio',
                'apellido' => 'Wendel',
                'genero' => 'Masculino',
                'fecha_nacimiento' => '1985-09-15',
                'direccion' => '',
                'telefono' => '978331835',
                'email' => 'jwendel@mail.mail'
            ]);

            // Crear un quiropractico asociado a la persona
            Quiropractico::create([
                'id_persona' => $persona->id,
                'id_sede' => 2, // ID de la sede
                'numero_colegiatura' => 'CMP0001',
                'datos_contacto' => 'Teléfono: 978331835, Email:',
                'estado' => 1 // 1: Activo, 0: Inactivo, 2: Vacaciones
            ]);

            //-----------------------------------------------------

            // Crear una persona para el quiropractico
            $persona = Persona::create([
                'tipo_documento' => 'DNI',
                'numero_documento' => '00000002',
                'nombre' => 'Alexis',
                'apellido' => 'Hernández García',
                'genero' => 'Masculino',
                'fecha_nacimiento' => '1985-09-15',
                'direccion' => '',
                'telefono' => '+52 722-850-2745',
                'email' => 'ahernandez@mail.mail'
            ]);

            // Crear un quiropractico asociado a la persona
            Quiropractico::create([
                'id_persona' => $persona->id,
                'id_sede' => 5, // ID de la sede
                'numero_colegiatura' => 'CMP0002',
                'datos_contacto' => 'Teléfono: +52 722-850-274, Email:',
                'estado' => 1 // 1: Activo, 0: Inactivo, 2: Vacaciones
            ]);

            //-----------------------------------------------------

            // Crear una persona para el quiropractico
            $persona = Persona::create([
                'tipo_documento' => 'DNI',
                'numero_documento' => '00000003',
                'nombre' => 'Felipe',
                'apellido' => 'Rocha',
                'genero' => 'Masculino',
                'fecha_nacimiento' => '1985-09-15',
                'direccion' => '',
                'telefono' => '983264653',
                'email' => 'frocha@mail.mail'
            ]);

            // Crear un quiropractico asociado a la persona
            Quiropractico::create([
                'id_persona' => $persona->id,
                'id_sede' => 4, // ID de la sede
                'numero_colegiatura' => 'CMP0003',
                'datos_contacto' => 'Teléfono: 983264653, Email:',
                'estado' => 1 // 1: Activo, 0: Inactivo, 2: Vacaciones
            ]);

            //-----------------------------------------------------

            // Crear una persona para el quiropractico
            $persona = Persona::create([
                'tipo_documento' => 'DNI',
                'numero_documento' => '00000004',
                'nombre' => 'Elizabeth',
                'apellido' => 'De Jesús Reyes',
                'genero' => 'Femenino',
                'fecha_nacimiento' => '1985-09-15',
                'direccion' => '',
                'telefono' => '982572625',
                'email' => 'edejesus@mail.mail'
            ]);

            // Crear un quiropractico asociado a la persona
            Quiropractico::create([
                'id_persona' => $persona->id,
                'id_sede' => 1, // ID de la sede
                'numero_colegiatura' => 'CMP0004',
                'datos_contacto' => 'Teléfono: 982572625, Email:',
                'estado' => 1 // 1: Activo, 0: Inactivo, 2: Vacaciones
            ]);

            //-----------------------------------------------------

            // Crear una persona para el quiropractico
            $persona = Persona::create([
                'tipo_documento' => 'DNI',
                'numero_documento' => '00000005',
                'nombre' => 'Elsy',
                'apellido' => 'Téllez',
                'genero' => 'Femenino',
                'fecha_nacimiento' => '1985-09-15',
                'direccion' => '',
                'telefono' => '964375515',
                'email' => 'etellez@mail.mail'
            ]);

            // Crear un quiropractico asociado a la persona
            Quiropractico::create([
                'id_persona' => $persona->id,
                'id_sede' => 3, // ID de la sede
                'numero_colegiatura' => 'CMP0005',
                'datos_contacto' => 'Teléfono: 964375515, Email:',
                'estado' => 1 // 1: Activo, 0: Inactivo, 2: Vacaciones
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error al insertar datos: ' . $e->getMessage());
        }
    }
}

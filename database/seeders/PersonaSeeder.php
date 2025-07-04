<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $persona1 = Persona::create([
        //     'tipo_documento' => 'DNI',
        //     'numero_documento' => '02345678',
        //     'nombre' => 'Miguel',
        //     'apellido' => 'García',
        //     'genero' => 'Masculino',
        //     'fecha_nacimiento' => '1990-05-10',
        //     'direccion' => 'Calle Ficticia 123',
        //     'telefono' => '987654321',
        //     'email' => 'miguel.garcia@example.com',
        // ]);

        // $persona2 = Persona::create([
        //     'tipo_documento' => 'DNI',
        //     'numero_documento' => '23456789',
        //     'nombre' => 'Ana',
        //     'apellido' => 'Gómez',
        //     'genero' => 'Femenino',
        //     'fecha_nacimiento' => '1985-08-15',
        //     'direccion' => 'Avenida Principal 456',
        //     'telefono' => '987654322',
        //     'email' => 'ana.gomez@example.com',
        // ]);

        // $persona3 = Persona::create([
        //     'tipo_documento' => 'DNI',
        //     'numero_documento' => '34567890',
        //     'nombre' => 'Carlos',
        //     'apellido' => 'Sánchez',
        //     'genero' => 'Masculino',
        //     'fecha_nacimiento' => '1992-03-20',
        //     'direccion' => 'Boulevard Central 789',
        //     'telefono' => '987654323',
        //     'email' => 'carlos.sanchez@example.com',
        // ]);

        // $persona4 = Persona::create([
        //     'tipo_documento' => 'DNI',
        //     'numero_documento' => '45678901',
        //     'nombre' => 'Laura',
        //     'apellido' => 'Martínez',
        //     'genero' => 'Femenino',
        //     'fecha_nacimiento' => '1988-11-05',
        //     'direccion' => 'Calle Secundaria 321',
        //     'telefono' => '987654324',
        //     'email' => 'laura.martinez@example.com',
        // ]);

        // $persona5 = Persona::create([
        //     'tipo_documento' => 'DNI',
        //     'numero_documento' => '56789012',
        //     'nombre' => 'Pedro',
        //     'apellido' => 'López',
        //     'genero' => 'Masculino',
        //     'fecha_nacimiento' => '1995-01-25',
        //     'direccion' => 'Avenida de las Américas 654',
        //     'telefono' => '987654325',
        //     'email' => 'pedro.lopez@example.com',
        // ]);

        // // Crear los usuarios correspondientes a cada persona
        // User::create([
        //     'login' => 'mgarcia',
        //     'email' => 'miguel.garcia@example.com',
        //     'password' => Hash::make('123456'),
        //     'rol' => 'Administrador',
        //     'id_persona' => $persona1->id,
        //     'id_sede' => 1,
        // ]);

        // User::create([
        //     'login' => 'agomez',
        //     'email' => 'ana.gomez@example.com',
        //     'password' => Hash::make('123456'),
        //     'rol' => 'Administrador',
        //     'id_persona' => $persona2->id,
        //     'id_sede' => 2,
        // ]);

        // User::create([
        //     'login' => 'csanchez',
        //     'email' => 'carlos.sanchez@example.com',
        //     'password' => Hash::make('123456'),
        //     'rol' => 'Administrador',
        //     'id_persona' => $persona3->id,
        //     'id_sede' => 3,
        // ]);

        // User::create([
        //     'login' => 'lmartinez',
        //     'email' => 'laura.martinez@example.com',
        //     'password' => Hash::make('123456'),
        //     'rol' => 'Administrador',
        //     'id_persona' => $persona4->id,
        //     'id_sede' => 4,
        // ]);

        // User::create([
        //     'login' => 'plopez',
        //     'email' => 'pedro.lopez@example.com',
        //     'password' => Hash::make('123456'),
        //     'rol' => 'Administrador',
        //     'id_persona' => $ray->id,
        //     'id_sede' => 5,
        // ]);

        // $ray = Persona::create([
        //     'tipo_documento' => 'Carnet de Extranjería',
        //     'numero_documento' => '000757457',
        //     'nombre' => 'Michael',
        //     'apellido' => 'Ray',
        //     'genero' => 'Masculino',
        //     'fecha_nacimiento' => '1971-04-09',
        //     'direccion' => '',
        //     'telefono' => '+13215253357',
        //     'email' => 'mikedavidray@yahoo.com',
        // ]);

        // $yeni = Persona::create([
        //     'tipo_documento' => 'DNI',
        //     'numero_documento' => '71035852',
        //     'nombre' => 'Yenifer',
        //     'apellido' => 'Orozco',
        //     'genero' => 'Femenino',
        //     'fecha_nacimiento' => '1996-05-06',
        //     'direccion' => '',
        //     'telefono' => '953371158',
        //     'email' => 'yenyferorozco65@gmail.com',
        // ]);

        // User::create([
        //     'login' => 'mray',
        //     'email' => 'mray@erptotal.online',
        //     'password' => Hash::make('123456'),
        //     'rol' => 'Superadministrador',
        //     'id_persona' => $ray->id,
        // ]);
        //User::create([
          //  'login' => 'yorozco',
            //'email' => 'yorozco@erptotal.online',
           // 'password' => Hash::make('123456'),
           // 'rol' => 'Superadministrador',
          //  'id_persona' => 249,
      //]);
      // $leti = Persona::create([
      //   'tipo_documento' => 'Carnet de Extranjería',
      //   'numero_documento' => '000000001',
      //   'nombre' => 'Leticia',
      //   'apellido' => 'Gasparetto',
      //   'genero' => 'Femenino',
      //   'fecha_nacimiento' => '1999-01-01',
      //   'direccion' => '',
      //   'telefono' => '',
      //   'email' => 'lgasparetto@erptotal.online'
      // ]);
      // User::create([
      //   'login' => 'lgasparetto',
      //   'email' => 'lgasparetto@erptotal.online',
      //   'password' => Hash::make("123456"),
      //   'rol' => 'Superadministrador',
      //   'id_persona' => 254
      // ]);

      // $tati = Persona::create([
      //   'tipo_documento' => 'DNI',
      //   'numero_documento' => '70661254',
      //   'nombre' => 'Tatiana Stephanie',
      //   'apellido' => 'Juárez Reyes',
      //   'genero' => 'Femenino',
      //   'fecha_nacimiento' => '2000-03-27',
      //   'direccion' => '',
      //   'telefono' => '+51940092383',
      //   'email' => 'tatianajuarezreyes20@gmail.com'
      // ]);
      User::create([
        'login' => 'tjuarez',
        'email' => 'tatianajuarezreyes20@gmail.com',
        'password' => Hash::make("123456"),
        'id_rol' => 2, // Superadministrador
        'id_persona' => 436
      ]);

      // // Desarrollador
      // Persona::create([
      //   'tipo_documento' => 'DNI',
      //   'numero_documento' => '00000001',
      //   'nombre' => 'Desarrollador',
      //   'apellido' => 'Desarrollador',
      //   'genero' => 'Masculino',
      //   'fecha_nacimiento' => '1999-12-31',
      //   'direccion' => '',
      //   'telefono' => '',
      //   'email' => 'des@erptotal.online'
      // ]);

      // // Superadministrador
      // Persona::create([
      //   'tipo_documento' => 'DNI',
      //   'numero_documento' => '00000002',
      //   'nombre' => 'Superadministrador',
      //   'apellido' => 'Superadministrador',
      //   'genero' => 'Masculino',
      //   'fecha_nacimiento' => '1999-12-31',
      //   'direccion' => '',
      //   'telefono' => '',
      //   'email' => 'super@erptotal.online'
      // ]);

      // // Administrador
      // Persona::create([
      //   'tipo_documento' => 'DNI',
      //   'numero_documento' => '00000003',
      //   'nombre' => 'Administrador',
      //   'apellido' => 'Administrador',
      //   'genero' => 'Masculino',
      //   'fecha_nacimiento' => '1999-12-31',
      //   'direccion' => '',
      //   'telefono' => '',
      //   'email' => 'admin@erptotal.online'
      // ]);

      // // Contador
      // Persona::create([
      //   'tipo_documento' => 'DNI',
      //   'numero_documento' => '00000004',
      //   'nombre' => 'Contador',
      //   'apellido' => 'Contador',
      //   'genero' => 'Masculino',
      //   'fecha_nacimiento' => '1999-12-31',
      //   'direccion' => '',
      //   'telefono' => '',
      //   'email' => 'cont@erptotal.online'
      // ]);

      // // CallCenter
      // Persona::create([
      //   'tipo_documento' => 'DNI',
      //   'numero_documento' => '00000005',
      //   'nombre' => 'CallCenter',
      //   'apellido' => 'CallCenter',
      //   'genero' => 'Masculino',
      //   'fecha_nacimiento' => '1999-12-31',
      //   'direccion' => '',
      //   'telefono' => '',
      //   'email' => 'call@erptotal.online'
      // ]);

      // // Paciente
      // Persona::create([
      //   'tipo_documento' => 'DNI',
      //   'numero_documento' => '00000006',
      //   'nombre' => 'Paciente',
      //   'apellido' => 'Paciente',
      //   'genero' => 'Masculino',
      //   'fecha_nacimiento' => '1999-12-31',
      //   'direccion' => '',
      //   'telefono' => '',
      //   'email' => 'pac@erptotal.online'
      // ]);
    }
}

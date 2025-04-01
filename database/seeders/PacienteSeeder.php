<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\Paciente;

class PacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::beginTransaction();

        try {
            // Crear una persona
            $persona = Persona::create([
                'tipo_documento' => 'DNI',
                'numero_documento' => '12345678',
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'genero' => 'Masculino',
                'fecha_nacimiento' => '1990-05-20',
                'direccion' => 'Av. Principal 123',
                'telefono' => '987654321',
                'email' => 'juan@example.com'
            ]);

            // Crear un paciente asociado a la persona
            Paciente::create([
                'id_persona' => $persona->id,
                'id_sede' => 1,
                'grupo_sanguineo' => 'O+',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error al insertar datos: ' . $e->getMessage());
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Crear una persona para el doctor
            $persona = Persona::create([
                'tipo_documento' => 'DNI',
                'numero_documento' => '87654321',
                'nombre' => 'Carlos',
                'apellido' => 'Ramírez',
                'genero' => 'Masculino',
                'fecha_nacimiento' => '1985-09-15',
                'direccion' => 'Calle Medica 456',
                'telefono' => '987654321',
                'email' => 'carlos@example.com'
            ]);

            // Crear un doctor asociado a la persona
            Doctor::create([
                'id_persona' => $persona->id,
                'id_sede' => 1, // ID de la sede
                'numero_colegiatura' => 'CMP12345',
                'especialidad' => 'Quiropráctico',
                'datos_contacto' => 'Teléfono: 987654321, Email: carlos@example.com',
                'estado' => 1 // 1: Activo, 0: Inactivo, 2: Vacaciones
            ]);

            // Otro doctor
            $persona2 = Persona::create([
                'tipo_documento' => 'DNI',
                'numero_documento' => '76543210',
                'nombre' => 'Ana',
                'apellido' => 'Torres',
                'genero' => 'Femenino',
                'fecha_nacimiento' => '1990-03-10',
                'direccion' => 'Av. Central 789',
                'telefono' => '912345678',
                'email' => 'ana@example.com'
            ]);

            Doctor::create([
                'id_persona' => $persona2->id,
                'id_sede' => 2,
                'numero_colegiatura' => 'CMP54321',
                'especialidad' => 'Pediatría',
                'datos_contacto' => 'Teléfono: 912345678, Email: ana@example.com',
                'estado' => 2 // De vacaciones
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error al insertar datos: ' . $e->getMessage());
        }
    }
}

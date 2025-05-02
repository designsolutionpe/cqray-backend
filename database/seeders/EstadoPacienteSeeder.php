<?php

namespace Database\Seeders;

use App\Models\EstadoPaciente;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EstadoPacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $estados = [
            ['nombre' => 'Nuevo'],
            ['nombre' => 'Reporte'],
            ['nombre' => 'Plan'],
            ['nombre' => 'Mantenimiento'],
            ['nombre' => 'Individual'],
            [ 'nombre' => 'Tratamiento'],
        ];
        EstadoPaciente::insert($estados);
    }
}

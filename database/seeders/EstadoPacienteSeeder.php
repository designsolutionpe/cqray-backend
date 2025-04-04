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
        EstadoPaciente::create([
            'nombre'=>'Nuevo'
        ]);

        EstadoPaciente::create([
            'nombre'=>'Reporte'
        ]);

        EstadoPaciente::create([
            'nombre'=>'Plan'
        ]);

        EstadoPaciente::create([
            'nombre'=>'Mantenimiento'
        ]);
    }
}

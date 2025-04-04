<?php

namespace Database\Seeders;

use App\Models\EstadoCita;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EstadoCitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EstadoCita::create([
            'nombre' => 'Confirmación pendiente'
        ]);
        EstadoCita::create([
            'nombre' => 'Confirmado'
        ]);
        EstadoCita::create([
            'nombre' => 'Atendido'
        ]);
        EstadoCita::create([
            'nombre' => 'Cancelado'
        ]);
    }
}

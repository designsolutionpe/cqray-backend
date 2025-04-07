<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuracion;

class ConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Configuracion::create([
            'nombre' => 'Clinica Quiropráctica Ray',
            'ruc' => '20487989852',
            'numero_sucursales' => 5,
            'imagen1' => null,
            'imagen2' => null,
            'estado' => 1
        ]);
    }
}

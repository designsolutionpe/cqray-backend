<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaArticulo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriaArticuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CategoriaArticulo::create([
            'nombre'=>'AJUSTES QUIROPRÁCTICOS',
            'estado'=>1
        ]);

        CategoriaArticulo::create([
            'nombre'=>'TERAPIAS COMPLEMENTARIAS',
            'estado'=>1
        ]);

        CategoriaArticulo::create([
            'nombre'=>'EVALUACIONES Y CONSULTAS',
            'estado'=>1
        ]);

        CategoriaArticulo::create([
            'nombre'=>'PROGRAMAS DE BIENESTAR',
            'estado'=>1
        ]);

        CategoriaArticulo::create([
            'nombre'=>'SOPORTES Y ORTESIS',
            'estado'=>1
        ]);

        CategoriaArticulo::create([
            'nombre'=>'PRODUCTOS DE CUIDADO PERSONAL',
            'estado'=>1
        ]);

        CategoriaArticulo::create([
            'nombre'=>'SUPLEMENTOS NUTRICIONALES',
            'estado'=>1
        ]);
    }
}

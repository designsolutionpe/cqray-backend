<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnidadMedidaArticulo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnidadMedidaArticuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $unidades = [
            [ "nombre" => "SESION | SN" ],
            [ "nombre" => "CONSULTA | CLS" ]
        ];
        UnidadMedidaArticulo::insert($unidades);
    }
}

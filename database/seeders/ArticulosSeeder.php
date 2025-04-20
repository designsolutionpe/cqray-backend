<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Articulo;

class ArticulosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $articulos = [
            [
                "id_sede" => 1,
                "id_categoria" => 1,
                "tipo_articulo" => 2,
                "id_unidad_medida" => 1,
                "nombre" => "PAQUETE N°3 - INDIVIDUAL",
                "cantidad" => 3,
                "detalle" => "",
                "precio_venta" => 60.00
            ],
            [
                "id_sede" => 1,
                "id_categoria" => 1,
                "tipo_articulo" => 2,
                "id_unidad_medida" => 1,
                "nombre" => "PAQUETE N°6 - INDIVIDUAL",
                "cantidad" => 6,
                "detalle" => "",
                "precio_venta" => 60.00
            ],
            [
                "id_sede" => 1,
                "id_categoria" => 1,
                "tipo_articulo" => 2,
                "id_unidad_medida" => 1,
                "nombre" => "PAQUETE N°3 - MANTENIMIENTO",
                "cantidad" => 3,
                "detalle" => "",
                "precio_venta" => 60.00
            ],
            [
                "id_sede" => 1,
                "id_categoria" => 1,
                "tipo_articulo" => 2,
                "id_unidad_medida" => 1,
                "nombre" => "PAQUETE N°6 - MANTENIMIENTO",
                "cantidad" => 6,
                "detalle" => "",
                "precio_venta" => 60.00
            ],
            [
                "id_sede" => 1,
                "id_categoria" => 1,
                "tipo_articulo" => 2,
                "id_unidad_medida" => 1,
                "nombre" => "PLAN COMPLETO - 36 SESIONES",
                "cantidad" => 36,
                "detalle" => "",
                "precio_venta" => 1200.00
            ],
            [
                "id_sede" => 1,
                "id_categoria" => 1,
                "tipo_articulo" => 2,
                "id_unidad_medida" => 1,
                "nombre" => "PLAN INCOMPLETO - 18 SESIONES",
                "cantidad" => 18,
                "detalle" => "",
                "precio_venta" => 700.00
            ]
        ];

        Articulo::insert($articulos);
    }
}

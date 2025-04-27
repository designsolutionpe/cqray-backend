<?php

namespace Database\Seeders;

use App\Models\TipoPago;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TipoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tipos = [
            ['nombre'=>'Transferencia Bancaria'],
            ['nombre'=>'Efectivo'],
            ['nombre'=>'Yape'],
            ['nombre'=>'Plin'],
        ];
        TipoPago::insert($tipos);
    }
}

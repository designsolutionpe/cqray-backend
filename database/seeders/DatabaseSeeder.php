<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Persona;
use Illuminate\Database\Seeder;
use Database\Seeders\PersonaSeeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\ArticulosSeeder;
use Database\Seeders\EstadoCitaSeeder;
use Database\Seeders\QuiropracticoSeeder;
use Database\Seeders\EstadoPacienteSeeder;
use Database\Seeders\CategoriaArticuloSeeder;
use Database\Seeders\UnidadMedidaArticuloSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EstadoCitaSeeder::class,
            EstadoPacienteSeeder::class,
            SedeSeeder::class,
            PacienteSeeder::class,
            // QuiropracticoSeeder::class,
            // HorarioSeeder::class,
            PersonaSeeder::class,
            UnidadMedidaArticuloSeeder::class,
            CategoriaArticuloSeeder::class,
            ArticulosSeeder::class,
            TipoPagoSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}

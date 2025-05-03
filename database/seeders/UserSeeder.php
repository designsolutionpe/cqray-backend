<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desarrollador
        User::create([
            'login' => 'dev',
            'email' => 'dev@erptotal.online',
            'password' => Hash::make('123456'),
            'id_rol' => 1, // Desarrollador
            'id_persona' => 2,
        ]);

        User::create([
          'login' => 'admin',
          'email' => 'admin@erptotal.online',
          'password' => Hash::make('123456'),
          'id_rol' => 2, // Superadministrador
          'id_persona' => 2
        ]);
    }
}

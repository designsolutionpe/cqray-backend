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
        // User::create([
        //     'login' => 'dev',
        //     'email' => 'dev@erptotal.online',
        //     'password' => Hash::make('123456'),
        //     'id_rol' => 1, // Desarrollador
        //     'id_persona' => 2,
        // ]);

        // User::create([
        //     'login' => 'superadmin',
        //     'email' => 'superadmin@erptotal.online',
        //     'password' => Hash::make('123456'),
        //     'id_rol' => 2, // Superadministrador
        //     'id_persona' => 2
        // ]);

        // User::create([
        //     'login' => 'admin',
        //     'email' => 'admin@erptotal.online',
        //     'password' => Hash::make('123456'),
        //     'id_rol' => 3, // Administrador
        //     'id_persona' => 2
        // ]);

        /*User::create([
            'login' => 'ssuybate',
            'email' => 'ssuybate@erptotal.online',
            'password' => Hash::make('saidsuyv'),
            'id_rol' => 1,
            'id_persona' => 14
        ]);

        User::create([
            'login' => 'smestanza',
            'email' => 'smestanza@erptotal.online',
            'password' => Hash::make('123456'),
            'id_rol' => 1,
            'id_persona' => 287
        ]);*/
    }
}

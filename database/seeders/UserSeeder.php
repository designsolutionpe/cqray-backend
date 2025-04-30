<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desarrollador
        User::create([
            'login' => 'saidsuyv',
            'email' => 'said@erptotal.online',
            'password' => Hash::make('123456'),
            'id_rol' => 1,
            'id_persona' => 1,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      //
      Role::create([
        'nombre' => 'Desarrollador'
      ]);
      Role::create([
        'nombre' => 'Superadministrador' 
      ]);
      Role::create([
        'nombre' => 'Administrador'
      ]);
      Role::create([
        'nombre' => 'Contador'
      ]);
      Role::create([
        'nombre' => 'CallCenter'
      ]);
      Role::create([
        'nombre' => 'Paciente'
      ]);
    }
}

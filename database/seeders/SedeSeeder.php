<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sede;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Sede::create([
            'nombre' => 'Sede Trujillo',
            'direccion' => 'Av. Universitaria 123, Trujillo',
            'telefono' => '044123456',
            'email' => 'trujillo@sede.com',
            'foto' => null,  // Establecer la foto como null
        ]);

        Sede::create([
            'nombre' => 'Sede Chiclayo',
            'direccion' => 'Av. Independencia 456, Chiclayo',
            'telefono' => '074123456',
            'email' => 'chiclayo@sede.com',
            'foto' => null,  // Establecer la foto como null
        ]);

        Sede::create([
            'nombre' => 'Sede Lima',
            'direccion' => 'Av. Pardo 789, Lima',
            'telefono' => '01 2345678',
            'email' => 'lima@sede.com',
            'foto' => null,  // Establecer la foto como null
        ]);

        Sede::create([
            'nombre' => 'Sede Cajamarca',
            'direccion' => 'Jr. San Martín 321, Cajamarca',
            'telefono' => '076123456',
            'email' => 'cajamarca@sede.com',
            'foto' => null,  // Establecer la foto como null
        ]);

        Sede::create([
            'nombre' => 'Sede Huancayo',
            'direccion' => 'Av. La Cultura 987, Huancayo',
            'telefono' => '064123456',
            'email' => 'huancayo@sede.com',
            'foto' => null,  // Establecer la foto como null
        ]);
    }
}

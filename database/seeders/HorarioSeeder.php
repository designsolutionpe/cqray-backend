<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Horario;
use Carbon\Carbon;

class HorarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $horario = Horario::create([
            'id_doctor' => 1,
            'dia' => 0, // Lunes
            'hora_inicio' => '08:00',
            'hora_fin' => '10:00',
            'duracion' => 30
        ]);

        // Generar bloques en detalle_horarios automáticamente
        $inicio = Carbon::createFromFormat('H:i', '08:00');
        $fin = Carbon::createFromFormat('H:i', '10:00');
        $duracion = 30;

        while ($inicio->lt($fin)) {
            $bloqueFin = (clone $inicio)->addMinutes($duracion);
            if ($bloqueFin->gt($fin)) break;

            $horario->detalleHorarios()->create([
                'hora_inicio' => $inicio->format('H:i'),
                'hora_fin' => $bloqueFin->format('H:i'),
            ]);

            $inicio = $bloqueFin;
        }
    }
}

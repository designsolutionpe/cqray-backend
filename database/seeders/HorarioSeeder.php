<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Horario;
use Carbon\Carbon;

class HorarioSeeder extends Seeder
{
    protected function genera_bloque_horario($horario,$hora_inicio,$hora_fin)
    {
        // Generar bloques en detalle_horarios automáticamente
        $inicio = Carbon::createFromFormat('H:i', $hora_inicio);
        $fin = Carbon::createFromFormat('H:i', $hora_fin);
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

    protected function genera_horario($id_quiropractico,$dia,$hora_inicio,$hora_fin)
    {
        $horario = Horario::create([
            'id_quiropractico' => $id_quiropractico,
            'dia' => $dia, // Lunes
            'hora_inicio' => $hora_inicio,
            'hora_fin' => $hora_fin,
            'duracion' => 30
        ]);

        $this->genera_bloque_horario($horario,$hora_inicio,$hora_fin);
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quiropracticos = [
            // JORGE MAURICIO
            "1" => [
                // Lunes
                "0" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '19:30'
                    ],
                ],
                // Martes
                "1" => [
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '19:30'
                    ],
                ],
                // Miercoles
                "2" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '19:30'
                    ],
                ],
                // Jueves
                "3" => [
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '19:30'
                    ],
                ],
                // Viernes
                "4" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '19:30'
                    ],
                ],
                // Sabado
                "5" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:00'
                    ],
                ]
            ],
            // ALEXIS HERNANDEZ
            "2" => [
                // Lunes
                "0" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '13:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Martes
                "1" => [
                    [
                        "hora_inicio" => '15:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Miercoles
                "2" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '13:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Jueves
                "3" => [
                    [
                        "hora_inicio" => '15:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Viernes
                "4" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '13:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Sabado
                "5" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:00'
                    ],
                ]
            ],
            // FELIPE ROCHA
            "3" => [
                // Lunes
                "0" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Martes
                "1" => [
                    [
                        "hora_inicio" => '15:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Miercoles
                "2" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Jueves
                "3" => [
                    [
                        "hora_inicio" => '15:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Viernes
                "4" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Sabado
                "5" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:00'
                    ],
                ]
            ],
            // ELIZABETH DE JESUS
            "4" => [
                // Lunes
                "0" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '13:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Martes
                "1" => [
                    [
                        "hora_inicio" => '15:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Miercoles
                "2" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '13:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Jueves
                "3" => [
                    [
                        "hora_inicio" => '15:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Viernes
                "4" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '13:00'
                    ],
                    [
                        "hora_inicio" => '16:00',
                        "hora_fin" => '20:00'
                    ],
                ],
                // Sabado
                "5" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:00'
                    ],
                ]
            ],
            // ELSY TELLEZ
            "5" => [
                // Lunes
                "0" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '13:00'
                    ],
                    [
                        "hora_inicio" => '15:00',
                        "hora_fin" => '19:30'
                    ],
                ],
                // Martes
                "1" => [
                    [
                        "hora_inicio" => '14:00',
                        "hora_fin" => '19:30'
                    ],
                ],
                // Miercoles
                "2" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '13:00'
                    ],
                    [
                        "hora_inicio" => '15:00',
                        "hora_fin" => '19:30'
                    ],
                ],
                // Jueves
                "3" => [
                    [
                        "hora_inicio" => '14:00',
                        "hora_fin" => '19:30'
                    ],
                ],
                // Viernes
                "4" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '13:00'
                    ],
                    [
                        "hora_inicio" => '15:00',
                        "hora_fin" => '19:30'
                    ],
                ],
                // Sabado
                "5" => [
                    [
                        "hora_inicio" => '9:00',
                        "hora_fin" => '12:30'
                    ],
                ]
            ],
        ];

        foreach($quiropracticos as $id => $dias)
        {
            foreach( $dias as $dia => $horas)
            {
                foreach( $horas as $hora )
                {
                    $inicio = $hora['hora_inicio'];
                    $fin = $hora['hora_fin'];
                    $this->genera_horario($id,$dia,$inicio,$fin);
                }
            }
        }
    }
}

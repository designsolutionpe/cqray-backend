<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Persona;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class VerificarDNIPacientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verificar-dni-pacientes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar el dni a traves de la API de RENIEC';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $personas = Persona::where('tipo_documento','DNI')
                            //->whereRaw('LENGTH(numero_documento) <> 8')
                            //->whereRaw('LENGTH(numero_documento) < 8')
                            ->get();
        $contenido = "";
        $contenido .= "ID|DNI|NOMBRE BD|NOMBRE RENIEC|OK|ERROR LOG" . PHP_EOL;
        foreach($personas as $p)
        {
            $dni = str_pad($p->numero_documento,8,'0',STR_PAD_LEFT);
            $response = Http::get(
                env('API_PERUDEV_URL')."/dni/simple?document=$dni&key=".env('API_PERUDEV_TOKEN')
            );

            if($response->successful())
            {
                try
                {
                    $body = $response->json();
                    $this->info("Respuesta: " . json_encode($body));
                    if($body['estado'])
                    {
                        $data = $body['resultado'];
                        $insert = implode(
                            '|',
                            [
                                $p->id,
                                str_pad($p->numero_documento,8,'0',STR_PAD_LEFT),
                                $p->nombre . ' ' . $p->apellido,
                                $data['nombre_completo'],
                                'Y'
                            ]
                        );

                        $this->info("found - $insert");
                        $contenido .= $insert . PHP_EOL;
                    }
                    else
                    {
                        $insert = implode(
                            '|',
                            [
                                $p->id,
                                str_pad($p->numero_documento,8,'0',STR_PAD_LEFT),
                                $p->nombre . ' ' . $p->apellido,
                                "NULL",
                                'N',
                                $body['mensaje']
                            ]
                        );
                        $this->info("not found - $insert");
                        $contenido .= $insert . PHP_EOL;
                    }
                }
                catch(Exception $e)
                {
                    $this->error('Algo salio mal dentro del successful - ' . $e->getMassage());
                }
            }
            else {
                $this->error('Algo salio mal ' . $response->status() . ' ' . $response->body());
                break;
            }
            sleep(1);
        }
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "paciente_dni_reniec_erroneos_{$timestamp}.csv";
        Storage::disk('local')->put($filename,$contenido);
        $this->info("Archivo generado: storage/app/{$filename}");
    }
}

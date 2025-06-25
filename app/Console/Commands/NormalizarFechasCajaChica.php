<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CajaChica;
use Carbon\Carbon;

class NormalizarFechasCajaChica extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:normalizar-fechas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Formatea las fechas al unicamente el dia señalado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener todos los registros
        $all = CajaChica::all();

        foreach($all as $item)
        {
            $og = $item->fecha;

            try{
                $fecha = Carbon::parse($og)->format("Y-m-d");
                $item->fecha = $fecha;
                $item->save();

                $this->info("Actualizado: ($og) -> ($fecha)");
            } catch(\Exception $e) 
            {
                $this->error("Inprocesable. $og");
            }

            $this->info("Comando terminado");
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Comprobante;

class AnularComprobante extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:anular-comprobante {serie}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anula especificamente constancias de pago para que no aparezcan en la caja chica.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $comprobante = Comprobante::where('numero',$this->argument('serie'))->first();

        if(empty($comprobante))
        {
            $this->error('El comprobante no existe');
            return 1;
        }

        $comprobante['fecha_anulado'] = now();
        $comprobante->save();

        return 0;
    }
}

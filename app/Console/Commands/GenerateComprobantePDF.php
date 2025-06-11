<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Comprobante;
use App\Models\DetalleComprobante;
use App\Services\PDFService;

class GenerateComprobantePDF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-comprobante-pdf {serie}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera un comprobante pdf a base del ID/Serie de comprobante';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $numero_serie = $this->argument('serie');

        $comprobante = Comprobante::where('numero',$numero_serie)->first()->toArray();
        
        $tipo_comprobante = $this->getTipoComprobante($comprobante['tipo_comprobante']);
        
        if(empty($comprobante))
        {
            $this->error('No se encontro el comprobante');
            return 1;
        }

        $detalles = DetalleComprobante::where('id_comprobante',$comprobante['id'])->get()->toArray();

        if(empty($detalles))
        {
            $this->error('El comprobante elegido no tiene detalles/productos [FATAL ERROR]');
            return 2;
        }

        if(empty($tipo_comprobante))
        {
            $this->error('El tipo de comprobante ingresado es erroneo');
            return 3;
        }

        $comprobante['numero_correlativo'] = $comprobante['numero'];
        $comprobante['detalles'] = $detalles;

        $url = PDFService::generateVoucher($tipo_comprobante,$comprobante);

        $comprobanteUpdate = Comprobante::where('serie',$comprobante['numero_correlativo'])
            ->update(['voucher_url' => $url]);

        return 0;
    }

    private function getTipoComprobante($codigo)
    {
        switch($codigo)
        {
            case 1:
                return 'boleta_voucher';
            case 2:
                return 'factura_voucher';
            case 3:
                return 'nota_credito_voucher';
            case 4:
                return 'constancia_pago_voucher';
            default:
                return null;
        }
    }
}

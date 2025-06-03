<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use PDF;
use App\Models\Sede;
use App\Models\Persona;
use App\Models\Articulo;
use App\Models\TipoPago;
use App\Helpers\MonedaHelper;

class PDFService
{
    protected static function setCodigoComprobante($comprobante)
    {
        switch($comprobante)
        {
            case 1: // Boleta
                return '03';
            case 2: // Factura
                return '01';
            case 3: // Nota de crèdito
                return '07';
            case 4: // Constancia de Pago
                return '00';
        }
    }

    public static function generateVoucher($voucherType,$data)
    {
        // Obtener informacion de sede
        $sede = Sede::find($data['id_sede']);

        // Codigo de tipo de comprobante
        $codigo = self::setCodigoComprobante($data['tipo_comprobante']);

        // Obtener informacion del cliente
        $cliente = Persona::find($data['id_persona']);

        // Obtener informacion de cada paquete en los detalles
        foreach($data['detalles'] as &$detalle)
        {
            $articulo = Articulo::find($detalle['id_articulo']);
            $detalle['articulo'] = $articulo;
        }

        // Obtener informacion de tipo de pago
        $tipo_de_pago = TipoPago::find($data['id_tipo_pago']);

        // Calculo de pago cliente
        $pago_cliente = MonedaHelper::convertirDineroAEntero($data['pago_cliente']);
        $pago_cliente_secundario = MonedaHelper::convertirDineroAEntero($data['pago_cliente_secundario'] ?? 0);
        $pago_total = $pago_cliente + $pago_cliente_secundario;

        $data['sede'] = $sede;
        $data['codigo_comprobante'] = $codigo;
        $data['cliente'] = $cliente;
        $data['tipo_pago'] = $tipo_de_pago;
        $data['pago_total'] = $pago_total;

        \Log::info("CHECK DATA",["data"=>$data]);

        $filename = $sede->ruc.'-'.$data['codigo_comprobante'].'-'.$data['serie'].'-'.$data['numero_correlativo'];

        $pdf = PDF::loadView("pdf.voucher.$voucherType",$data)
                ->setPaper("a5","landscape");

        Storage::disk('public')->put("$filename.pdf", $pdf->output());

        return Storage::url($filename).".pdf";
    }
}

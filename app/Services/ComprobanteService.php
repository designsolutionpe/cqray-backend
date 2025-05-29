<?php

namespace App\Services;
use App\Models\Comprobante;

class ComprobanteService
{
    protected $comprobante;
    public function handler(Comprobante $comprobante)
    {
        if($comprobante->tipo == "2")
        {
            $this->comprobante = $comprobante;
            if($this->verificarDeuda()) return;
            // $this->generarSesiones();
        }
    }

    /*
     * Function Name: verificarDeuda
     * Description:
     *      Verifica si existe una sesion con las
     *      siguientes condiciones:
     *
     *          - Es el mismo paquete
     *          - Este en deuda
     *          - Este activo
     *
     *      en caso se cumplan las condiciones, el
     *      monto pagado en el comprobante pasa a
     *      restar la deuda actual en el paquete
     *      activo
     * */

    protected function verificarDeuda()
    {
        \Log::info('VERIFICAR DEUDA',["data" => $this->comprobante]);
    }
}

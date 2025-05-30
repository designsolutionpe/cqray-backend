<?php

namespace App\Services;
use App\Models\Comprobante;

class ComprobanteService
{
    protected $comprobante;

    /**
     * Function: handler
     * Description:
     *      Funcion que realizara cierta cantidad de
     *      verificaciones como:
     *          - Verificar que no exista deuda, y si
     *            existe, regularizar con el pago
     *            realizado
     *          - Generar sesiones para pacientes
     */
    public function handler(Comprobante $comprobante)
    {
        if(
            $comprobante->tipo == "2" && // Servicio
            Paciente::where('id_persona',$comprobante['id_persona'])->exists()
        )
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
        foreach($this->comprobante['detalles'] as $detalle)
        {
            $sesiones = HistoriaClinica::where([
                'id_articulo' => $detalle['id_articulo'],
                'estado_pago' => 2,
                'activo' => 1,
            ])->get();

            if($sesiones)
            {
                $comprobanteRef = Comprobante::find($sesiones->first()->id_comprobante);
                $total = $this->comprobante['pago_cliente_total'];
                if( ($total + -($comprobanteRef->deuda)) == 0 )
                {
                }
            }
        }
    }
}

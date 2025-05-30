<?php

namespace App\Services;
use App\Models\Paciente;
use App\Models\Comprobante;
use App\Helpers\MonedaHelper;
use App\Models\HistoriaClinica;

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
    public function handler($comprobante)
    {
        $paciente = Paciente::where('id_persona',$comprobante['id_persona']);
        if(
            $comprobante['tipo'] == "2" && // Servicio
            $paciente->exists()
        )
        {
            $this->comprobante = $comprobante;

            // VERIFICA SI TIENE ALGUN PAQUETE ACTIVO

            $paquete_activo = HistoriaClinica::where([
                'id_paciente' => $paciente->id,
                'activo' => 1
            ]);

            // Si tiene un paquete activo, verifica los detalles
            if($paquete_activo->exists())
            {
                // Verifica si los detalles tiene un paquete
                // en deuda, y si no, genera mas paquetes inactivos
                foreach($comprobante['detalle'] as $detalle)
                {
                    // Si es que el paquete activo, tiene deuda
                    // salda la deuda o la regula y continua con el siguiente
                    // paquete
                    if($this->verificarDeuda($detalle)) continue;
                    // Si el paquete activo, no tiene deuda
                    // genera otro paquete en estado inactivo
                    // else $this->generaPaqueteInactivo($detalle);
                }
            }
            // Si el paciente no tiene paquetes activos,
            // verifica que no tenga deudas anteriores
            // si no tiene genera el paquete activo y los demas
            // en inactivos
            // si si tiene deudas, no genera ningun paquete
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

    protected function verificarDeuda($detalle)
    {
            \Log::info("DETALLE",["data"=>$detalle]);
            $sesiones = HistoriaClinica::where([
                'id_articulo' => $detalle['id_articulo'],
                'estado_pago' => 2,
                'activo' => 1,
            ])->get();

            if(count($sesiones) == 0) return false;

            foreach($sesiones as $sesion)
            {
                $comprobanteRef = Comprobante::find($sesion->id_comprobante);
                if(!$comprobanteRef)
                {
                    \Log::info("COMPROBANTE DE DETALLE ES NULL",["ID COMPROBANTE"=>$sesion->id_comprobante,"SESION/HISTORIA CLINICA" => $sesion->id]);
                    continue;
                }

                $deuda = MonedaHelper::convertirDineroAEntero($comprobanteRef->deuda);
                $pago_cliente = MonedaHelper::convertirDineroAEntero($this->comprobante['pago_cliente']);
                $pago_cliente_sec = MonedaHelper::convertirDineroAEntero($this->comprobante['pago_cliente_secundario'] ?? 0);
                $total_pago = $pago_cliente + $pago_cliente_sec;
                
                if($total_pago >= abs($deuda)) // Deuda saldada
                {
                    // AQUI DEBERIA ACTUALIZAR EL CAMPO 'estado_pago' A 1
                    HistoriaClinica::where('id',$sesion->id)->update('estado_pago',1);
                }
            }
    }
}

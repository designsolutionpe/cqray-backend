<?php

namespace App\Services;
use App\Models\Paciente;
use App\Models\Comprobante;
use App\Helpers\MonedaHelper;
use App\Models\HistoriaClinica;
use App\Models\Articulo;

class ComprobanteService
{
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
    public function handler($post,$stored)
    {
        $paciente = Paciente::where('id_persona',$post['id_persona'])->first();
        if(
            $post['tipo'] == "2" && // Servicio
            $paciente
        )
        {
            // VERIFICA SI LOS PAQUETES PASADOS SON ACTIVOS
            // Y CON DEUDA

            foreach($post["detalles"] as $detalle)
            {

                $paquetes = HistoriaClinica::where([
                    'id_paciente' => $paciente->id,
                    'activo' => 1
                ])->get();

                // Hay paquetes activos
                if($paquetes->isNotEmpty())
                {
                    $paquete_activo = $paquetes->first();
                    // Si el paquete activo esta en deuda
                    // y es igual al paquete del detalle:
                    //  - Regularizar con lo pagado
                    if($paquete_activo->estado_pago == 2 && $paquete_activo->id_articulo == $detalle['id_articulo'])
                    {
                    $ref = Comprobante::find($paquete_activo->id_comprobante);
                    if($this->calcularDeuda(
                        $ref['deuda'],
                        $post['pago_cliente'],
                        $post['pago_cliente_secundario'] ?? 0
                    ))
                    {
                        HistoriaClinica::where('uuid',$paquete_activo->uuid)->update(['estado_pago'=>1]);
                    }
                    }
                    // Si no hay deuda y/o no es el paquete:
                    //  - Guarda como paquete inactivo
                    //    ya que existe un paquete activo
                    else
                    {
                        $articulo = Articulo::find($detalle['id_articulo']);

                        $estado_pago = $this->calcularDeuda(
                            $post['total'],
                            $post['pago_cliente'],
                            $post['pago_cliente_secundario'] ?? 0
                        ) ? 1 : 2;

                        $this->generarPaquetes(
                            $paciente->id,
                            $detalle['id_articulo'],
                            $post['id_sede'],
                            $stored->id,
                            $estado_pago,
                            0,
                            $articulo->cantidad
                        );
                    }

                    // Guarda paquete en modo inactivo
                }
                // Si no hay paquete activo
                else
                {
                    $articulo = Articulo::find($detalle['id_articulo']);
                    $estado_pago = $this->calcularDeuda(
                        $post['total'],
                        $post['pago_cliente'],
                        $post['pago_cliente_secundario'] ?? 0
                    ) ? 1 : 2;

                    $this->generarPaquetes(
                        $paciente->id,
                        $detalle['id_articulo'],
                        $post['id_sede'],
                        $stored->id,
                        $estado_pago,
                        1,
                        $articulo->cantidad
                    );
                }
            }
        }
    }

    protected function calcularDeuda($deuda,$pago_pr,$pago_sc)
    {
        $deuda = MonedaHelper::convertirDineroAEntero($deuda);
        $pago_pr = MonedaHelper::convertirDineroAEntero($pago_pr);
        $pago_sc = MonedaHelper::convertirDineroAEntero($pago_sc);
        $total = $pago_pr + $pago_sc;
        \Log::info("CALCULAR DEUDA8",["total"=>$total,"deuda"=>$deuda]);
        return ($total >= abs($deuda));
    }

    /**
     * Function Name: generarPaquetes
     * Description:
     *      Genera paquetes al paciente
     *      especificado
    **/
    protected function generarPaquetes(
        $id_paciente,
        $id_articulo,
        $id_sede,
        $id_comprobante,
        $estado_pago,
        $activo,
        $cantidad_total
    )
    {
        $uuid = bin2hex(random_bytes(16));
        for($n = 0; $n < $cantidad_total; $n++)
        {
            HistoriaClinica::create([
                'id_paciente' => $id_paciente,
                'id_sede' => $id_sede,
                'id_articulo' => $id_articulo,
                'estado_pago' => $estado_pago,
                'activo' => $activo,
                'uuid' => $uuid,
                'id_comprobante' => $id_comprobante
            ]);
        }
    }
}

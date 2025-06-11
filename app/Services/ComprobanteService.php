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
        $paciente = Paciente::where(
                        'id_persona',
                        $post['id_persona']
                    )->first();

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
                    'id_paciente' => $paciente->id
                ])->get();

                $tieneActivos = $paquetes->contains(
                    fn ($paquete) => $paquete->activo
                );

                // Hay paquetes
                if($paquetes->isNotEmpty())
                {
                    // Filtra por detalle actual
                    $paquete_detalle = $paquetes->filter(
                        fn($p) => 
                            $p->id_articulo == 
                            $detalle['id_articulo']
                    );
                    $paquete_deuda = $paquete_detalle->where(
                        "estado_pago" , 2
                    )
                    ->groupBy("uuid")->keys()->first();
                    // Verifica hay activos
                    $paquete_activo = 
                        $paquete_detalle->contains(
                            fn($p) => $p->activo 
                        );

                    if($paquete_deuda && !empty($paciente->id_articulo_deuda) && $paciente->id_articulo_deuda == $detalle['id_articulo'])
                    {
                        \Log::info(
                            "PAQUETE EN DEUDA",
                            [
                                'deuda' => $paciente->deuda,
                                'principal' => $post['pago_cliente'],
                                'secundario' => $post['pago_cliente_secundario'] ?? 0,
                                'resultado' => 
                                $this->saldoDeuda(
                                    $paciente->deuda / 100,
                                    $post['pago_cliente'],
                                    $post['pago_cliente_secundario'] ?? 0
                                )
                            ]
                        );
                        if($this->saldoDeuda(
                            $paciente->deuda / 100,
                            $post['pago_cliente'],
                            $post['pago_cliente_secundario'] ?? 0
                        ))
                        {
                            HistoriaClinica::where("uuid",$paquete_deuda)->update(["estado_pago"=>1]);
                            $paciente['deuda'] = null;
                            $paciente['id_articulo_deuda'] = null;
                            $paciente->save();
                            continue;
                        }
                        else
                        {
                            $paciente['deuda'] = $this->calcularDeuda(
                                $paciente->deuda / 100,
                                $post['pago_cliente'],
                                $post['pago_cliente_secundario'] ?? 0
                            );
                            $paciente['id_articulo_deuda'] = $detalle['id_articulo'];
                            $paciente->save();
                            \Log::info("deuda actualizada",["paciente" => $paciente]);
                            continue;
                        }
                    }
                    else
                    {
                        \Log::info("CHECK ARTICULO",['arti' => $detalle]);
                        $articulo = Articulo::find($detalle['id_articulo']);
                        $estado_pago = $this->saldoDeuda(
                            $stored['total'],
                            $post['pago_cliente'],
                            $post['pago_cliente_secundario'] ?? 0
                        ) ? 1 : 2;

                        $this->generarPaquetes(
                            $paciente->id,
                            $detalle['id_articulo'],
                            $post['id_sede'],
                            $stored->id,
                            $estado_pago,
                            $tieneActivos ? 0 : 1,
                            $articulo->cantidad
                        );

                        if($estado_pago == 2)
                        {
                            $paciente['deuda'] = $this->calcularDeuda(
                                $stored['total'],
                                $post['pago_cliente'],
                                $post['pago_cliente_secundario'] ?? 0
                            );
                            $paciente['id_articulo_deuda'] = $articulo->id;
                            $paciente->save();
                        }
                    }
                    // Guarda paquete en modo inactivo
                }
                // Si no hay paquetes
                else
                {
                    $articulo = Articulo::find($detalle['id_articulo']);
                    $estado_pago = $this->saldoDeuda(
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

                    if($estado_pago == 2)
                    {
                        $paciente['deuda'] = $this->calcularDeuda(
                            $post['total'],
                            $post['pago_cliente'],
                            $post['pago_cliente_secundario'] ?? 0
                        );
                        $paciente['id_articulo_deuda'] = $articulo->id;
                        $paciente->save();
                    }
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
        $resultado = abs($deuda) - $total;
        \Log::info("CALCULAR DEUDA",['total'=>$total,'deuda'=>$deuda,'resultado' => $resultado]);
        return $resultado > 0 ? $resultado : 0;
    }

    protected function saldoDeuda($deuda,$pago_pr,$pago_sc)
    {
        $calculo = $this->calcularDeuda($deuda,$pago_pr,$pago_sc);
        return ($calculo <= 0);
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

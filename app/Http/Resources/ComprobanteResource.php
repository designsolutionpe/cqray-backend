<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComprobanteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo_comprobante' => $this->tipo_comprobante,
            'tipo' => $this->tipo,
            'serie' => $this->serie,
            'numero' => $this->numero,
            'fecha_emision' => $this->fecha_emision,
            'moneda' => $this->moneda,
            'tipo_cambio' => $this->tipo_cambio,
            'igv' => $this->igv,
            'subtotal' => $this->subtotal,
            'monto_igv' => $this->monto_igv,
            'descuento' => $this->descuento,
            'total' => $this->total,
            'pago_cliente' => $this->pago_cliente,
            'vuelto' => $this->vuelto,

            'persona' => [
                'id' => $this->persona->id,
                'nombre' => $this->persona->nombre,
                'apellido' => $this->persona->apellido,
            ],

            'sede' => [
                'id' => $this->sede->id,
                'nombre' => $this->sede->nombre,
            ],
            'detalles' => $this->detalles->map(function ($detalle) {
                return [
                    'id' => $detalle->id,
                    'id_producto' => $detalle->id_producto,
                    'cantidad' => $detalle->cantidad,
                    'descuento' => $detalle->descuento,
                    'precio_unitario' => $detalle->precio_unitario,
                    'total_producto' => $detalle->total_producto,
                ];
            }),
        ];
    }
}

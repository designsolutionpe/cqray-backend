<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotaCreditoResource extends JsonResource
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
            'id_comprobante' => $this->id_comprobante,
            'tipo_comprobante' => $this->tipo_comprobante,
            'tipo' => $this->tipo,
            'serie' => $this->serie,
            'numero' => $this->numero,
            'motivo' => $this->motivo,
            'comentario' => $this->comentario,
            'fecha_emision' => $this->fecha_emision,
            'moneda' => $this->moneda,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'monto_igv' => $this->monto_igv,
            'descuento' => $this->descuento,

            'comprobante' => [
                'id' => $this->comprobante->id,
                'serie' => $this->comprobante->serie,
                'numero' => $this->comprobante->numero,
            ],

            'sede' => [
                'id' => $this->sede->id,
                'nombre' => $this->sede->nombre,
            ],

            'detalles' => $this->detalles->map(function ($detalle) {
                return [
                    'id' => $detalle->id,
                    'id_articulo' => $detalle->id_articulo,
                    'nombre_articulo' => $detalle->articulo ? $detalle->articulo->nombre : null,
                    'cantidad' => $detalle->cantidad,
                    'descuento' => $detalle->descuento,
                    'precio_unitario' => $detalle->precio_unitario,
                    'total_producto' => $detalle->total_producto,
                ];
            }),
        ];
    }
}

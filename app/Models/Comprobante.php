<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleComprobante;
use App\Models\Persona;

class Comprobante extends Model
{
    /** @use HasFactory<\Database\Factories\ComprobanteFactory> */
    use HasFactory;

    protected $fillable = [
        'tipo_comprobante', 'tipo', 'id_persona', 'serie', 'numero', 'fecha_emision',
        'moneda', 'tipo_cambio', 'igv', 'subtotal', 'monto_igv', 'descuento',
        'total', 'pago_cliente', 'vuelto'
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleComprobante::class, 'id_comprobante');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }
}

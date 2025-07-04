<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleComprobante;
use App\Models\Persona;
use App\Models\Sede;

class Comprobante extends Model
{
    /** @use HasFactory<\Database\Factories\ComprobanteFactory> */
    use HasFactory;

    protected $fillable = [
        'tipo_comprobante',
        'id_sede',
        'tipo',
        'id_persona',
        'serie',
        'numero',
        'fecha_emision',
        'moneda',
        'tipo_cambio',
        'igv',
        'subtotal',
        'monto_igv',
        'descuento',
        'total',
        'id_tipo_pago',
        'pago_cliente',
        'vuelto',
        'deuda',
        'id_tipo_pago_secundario',
        'pago_cliente_secundario',
        'voucher_url'
    ];

    public static function tipoComprobante()
    {
        return [
            '1' => 'B001',
            '2' => 'F001',
            '3' => 'N001',
            '4' => 'CP01'
        ];
    }

    public function detalles()
    {
        return $this->hasMany(DetalleComprobante::class, 'id_comprobante');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'id_sede');
    }

    public function tipo_pago()
    {
        return $this->belongsTo(TipoPago::class,'id_tipo_pago');
    }

    public function tipo_pago_secundario()
    {
        return $this->belongsTo(TipoPago::class,'id_tipo_pago_secundario');
    }
}

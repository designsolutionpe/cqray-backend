<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comprobante;
use App\Models\Item;

class DetalleComprobante extends Model
{
    /** @use HasFactory<\Database\Factories\DetalleComprobanteFactory> */
    use HasFactory;

    protected $fillable = [
        'id_comprobante', 'id_producto', 'cantidad', 'descuento', 'precio_unitario', 'total_producto'
    ];

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'id_comprobante');
    }

    public function producto()
    {
        return $this->belongsTo(Item::class, 'id_producto');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comprobante;
use App\Models\Articulo;

class DetalleComprobante extends Model
{
    /** @use HasFactory<\Database\Factories\DetalleComprobanteFactory> */
    use HasFactory;

    protected $fillable = [
        'id_comprobante', 'id_articulo', 'cantidad', 'descuento', 'precio_unitario', 'total_producto'
    ];

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'id_comprobante');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'id_articulo');
    }
}

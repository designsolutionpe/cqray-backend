<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleNota extends Model
{
    /** @use HasFactory<\Database\Factories\DetalleNotaFactory> */
    use HasFactory;

    protected $table = 'detalle_notas';

    protected $fillable = [
        'id_nota_credito',
        'id_articulo',
        'cantidad',
        'descuento',
        'precio_unitario',
        'total_producto',
    ];

    // Relación con la nota de crédito
    public function notaCredito()
    {
        return $this->belongsTo(NotaCredito::class, 'id_nota_credito');
    }

    // Relación con los artículos
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'id_articulo');
    }
}

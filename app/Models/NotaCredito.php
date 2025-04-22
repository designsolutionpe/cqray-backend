<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaCredito extends Model
{
    /** @use HasFactory<\Database\Factories\NotaCreditoFactory> */
    use HasFactory;

    protected $table = 'notas_creditos';

    protected $fillable = [
        'tipo_comprobante',
        'id_sede',
        'id_comprobante',
        'motivo',
        'comentario',
        'fecha_emision',
        'moneda',
        'subtotal',
        'monto_igv',
        'descuento',
        'total',
    ];

    // Relación con la tabla comprobantes
    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'id_comprobante');
    }
 
    // Relación con los detalles de la nota de crédito
    public function detalles()
    {
        return $this->hasMany(DetalleNota::class, 'id_nota_credito');
    }
 
     // Relación con la tabla sedes
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'id_sede');
    }

}

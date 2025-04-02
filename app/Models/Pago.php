<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sede;

class Pago extends Model
{
    /** @use HasFactory<\Database\Factories\PagoFactory> */
    use HasFactory;

    protected $fillable = [
        'id_sede', 
        'metodo_pago',
        'moneda',
        'detalle_concepto',
        'numero_cuenta',
        'estado',
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'id_sede');
    }
}

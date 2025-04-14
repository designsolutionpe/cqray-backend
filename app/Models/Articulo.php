<?php

namespace App\Models;

use App\Models\Sede;
use App\Models\CategoriaArticulo;
use App\Models\UnidadMedidaArticulo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Articulo extends Model
{
    /** @use HasFactory<\Database\Factories\ArticuloFactory> */
    use HasFactory;

    protected $table = 'articulos';

    protected $fillable = [
        'id_sede',
        'id_categoria',
        'id_unidad_medida',
        'tipo_articulo',
        'nombre',
        'detalle',
        'cantidad',
        'limite_cantidad',
        'precio_venta',
        'precio_mayor',
        'precio_distribuidor',
        'precio_compra',
        'tipo_tributo',
        'tributo',
        'codigo_internacional',
        'nombre_tributo'
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class,'id_sede');
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaArticulo::class,'id_categoria');
    }

    public function unidad_medida()
    {
        return $this->belongsTo(UnidadMedidaArticulo::class,'id_unidad_medida');
    }
}

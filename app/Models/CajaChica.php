<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaChica extends Model
{
    //
    protected $table = 'caja_chicas';

    protected $fillable = [
        'tipo',
        'balance',
        'id_sede',
        'fecha',
        'flg_inicial',
        'flg_terminal',
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class,'id_sede');
    }
}

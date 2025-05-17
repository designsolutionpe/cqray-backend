<?php

namespace App\Services;

use App\Models\CajaChica;

class CajaChicaService
{
  public static function checkIfOpened($sede)
  {
    $lastItem = CajaChica::where('id_sede',$sede)->get()->last();
    return $lastItem;
  }
}
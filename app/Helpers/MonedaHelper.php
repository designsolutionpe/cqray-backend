<?php

namespace App\Helpers;

class MonedaHelper
{
  public static function convertirDineroAEntero($valor):int {
    return (int) round(((float) valor) * 100);
  }
}
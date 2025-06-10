<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\CajaChica;

class EstadisticasController extends Controller
{
    use ApiResponser;
    /**
     * Return stats for main dashboard
     */
    public function dashboard()
    {
        $stats = CajaChica::selectRaw("DATE_FORMAT(fecha,'%M') as mes, SUM(balance) as total")
            ->whereYear('fecha',2025)
            ->groupByRaw('MONTH("fecha")')
            ->orderByRaw('MONTH("fecha")')
            ->get();
        return $this->successResponse($stats);
    }
}

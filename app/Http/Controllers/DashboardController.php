<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Suscripcion;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $suscripciones = Suscripcion::query()
            ->activas()
            ->with(['cliente', 'plan'])
            ->get();

        $porEstado = $suscripciones->groupBy('estado');

        $cobradoEsteMes = Pago::query()
            ->whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto');

        $proximosVencimientos = $suscripciones
            ->whereIn('estado', ['tolerancia', 'vencido'])
            ->sortBy('fecha_proximo_pago')
            ->take(8);

        return view('dashboard', [
            'alDia' => $porEstado->get('al_dia', collect())->count(),
            'enTolerancia' => $porEstado->get('tolerancia', collect())->count(),
            'vencidos' => $porEstado->get('vencido', collect())->count(),
            'cobradoEsteMes' => $cobradoEsteMes,
            'proximosVencimientos' => $proximosVencimientos,
        ]);
    }
}

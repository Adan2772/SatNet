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
            'ingresosPorMes' => $this->ingresosUltimosMeses(),
        ]);
    }

    private function ingresosUltimosMeses(int $meses = 6): \Illuminate\Support\Collection
    {
        return collect(range($meses - 1, 0))->map(function (int $mesesAtras) {
            $mes = now()->subMonthsNoOverflow($mesesAtras);

            $total = Pago::query()
                ->whereMonth('fecha_pago', $mes->month)
                ->whereYear('fecha_pago', $mes->year)
                ->sum('monto');

            return [
                'etiqueta' => $mes->translatedFormat('M Y'),
                'total' => (float) $total,
            ];
        });
    }
}

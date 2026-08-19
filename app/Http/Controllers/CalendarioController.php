<?php

namespace App\Http\Controllers;

use App\Models\Suscripcion;
use Illuminate\View\View;

class CalendarioController extends Controller
{
    public function index(): View
    {
        $suscripciones = Suscripcion::query()
            ->activas()
            ->with(['cliente', 'plan'])
            ->get()
            ->groupBy('dia_pago')
            ->sortKeys();

        $grupos = [
            'Días 1–10' => $this->filtrarRango($suscripciones, 1, 10),
            'Días 11–20' => $this->filtrarRango($suscripciones, 11, 20),
            'Días 21–31' => $this->filtrarRango($suscripciones, 21, 31),
        ];

        return view('calendario.index', [
            'grupos' => $grupos,
            'total' => $suscripciones->flatten()->count(),
        ]);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<int, Suscripcion>>  $porDia
     * @return \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<int, Suscripcion>>
     */
    private function filtrarRango($porDia, int $desde, int $hasta)
    {
        return $porDia->filter(fn ($_, int $dia) => $dia >= $desde && $dia <= $hasta);
    }
}

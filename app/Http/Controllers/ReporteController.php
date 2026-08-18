<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function pagos(Request $request): View
    {
        [$desde, $hasta] = $this->rango($request);

        $pagos = $this->pagosEnRango($desde, $hasta);

        return view('reportes.pagos', [
            'pagos' => $pagos,
            'total' => $pagos->sum('monto'),
            'desde' => $desde,
            'hasta' => $hasta,
        ]);
    }

    public function pagosCsv(Request $request)
    {
        [$desde, $hasta] = $this->rango($request);

        $pagos = $this->pagosEnRango($desde, $hasta);

        $nombreArchivo = "cobros_{$desde->format('Y-m-d')}_{$hasta->format('Y-m-d')}.csv";

        return response()->streamDownload(function () use ($pagos) {
            $salida = fopen('php://output', 'w');

            fputcsv($salida, ['Fecha', 'Cliente', 'Plan', 'Monto', 'Recibo', 'Notas']);

            foreach ($pagos as $pago) {
                fputcsv($salida, [
                    $pago->fecha_pago->format('Y-m-d'),
                    $pago->suscripcion->cliente->nombre,
                    $pago->suscripcion->plan->nombre,
                    number_format((float) $pago->monto, 2, '.', ''),
                    $pago->recibo->folio ?? '',
                    $pago->notas,
                ]);
            }

            fclose($salida);
        }, $nombreArchivo, ['Content-Type' => 'text/csv']);
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function rango(Request $request): array
    {
        $desde = $request->date('desde')?->startOfDay() ?? now()->startOfMonth();
        $hasta = $request->date('hasta')?->endOfDay() ?? now()->endOfMonth();

        return [$desde, $hasta];
    }

    private function pagosEnRango(Carbon $desde, Carbon $hasta)
    {
        return Pago::query()
            ->whereBetween('fecha_pago', [$desde, $hasta])
            ->with('suscripcion.cliente', 'suscripcion.plan', 'recibo')
            ->orderBy('fecha_pago')
            ->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\ReciboPago;
use App\Models\Recibo;
use App\Models\Suscripcion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PagoController extends Controller
{
    public function store(Request $request, Suscripcion $suscripcion): RedirectResponse
    {
        $datos = $request->validate([
            'monto' => ['required', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string', 'max:255'],
        ]);

        $pago = $suscripcion->pagos()->create([
            'monto' => $datos['monto'],
            'fecha_pago' => now()->toDateString(),
            'notas' => $datos['notas'] ?? null,
        ]);

        $recibo = Recibo::create([
            'pago_id' => $pago->id,
            'folio' => 'REC-'.now()->format('Ymd').'-'.str_pad((string) $pago->id, 4, '0', STR_PAD_LEFT),
        ]);

        $suscripcion->update([
            'fecha_proximo_pago' => Suscripcion::siguienteCiclo(
                $suscripcion->fecha_proximo_pago,
                $suscripcion->dia_pago,
            ),
        ]);

        if ($suscripcion->cliente->correo) {
            Mail::to($suscripcion->cliente->correo)->send(new ReciboPago($recibo));
            $recibo->update(['enviado_en' => now()]);
        }

        return redirect()
            ->route('clientes.show', $suscripcion->cliente)
            ->with('status', "Pago registrado y recibo {$recibo->folio} enviado.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Enlace;
use App\Models\Suscripcion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EnlaceController extends Controller
{
    public function edit(Suscripcion $suscripcion): View
    {
        return view('enlaces.form', [
            'suscripcion' => $suscripcion,
            'enlace' => $suscripcion->enlace ?? new Enlace(),
        ]);
    }

    public function update(Request $request, Suscripcion $suscripcion): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'ip_asignada' => [
                'nullable', 'ip',
                Rule::unique('enlaces', 'ip_asignada')->ignore($suscripcion->enlace?->id),
            ],
            'mac_address' => ['nullable', 'string', 'max:32'],
            'tipo_antena' => ['nullable', 'string', 'max:255'],
            'nodo' => ['nullable', 'string', 'max:255'],
            'numero_serie' => ['nullable', 'string', 'max:255'],
            'fecha_instalacion' => ['required', 'date'],
            'estado' => ['required', Rule::in(['activo', 'suspendido', 'falla'])],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $suscripcion->enlace()->updateOrCreate([], $datos);

        return redirect()
            ->route('clientes.show', $suscripcion->cliente)
            ->with('status', 'Enlace guardado correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Plan;
use App\Models\Suscripcion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q'));

        $clientes = Cliente::query()
            ->with(['suscripciones' => fn ($q) => $q->with('plan')])
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('nombre', 'like', "%{$busqueda}%")
                        ->orWhere('telefono', 'like', "%{$busqueda}%")
                        ->orWhere('correo', 'like', "%{$busqueda}%");
                });
            })
            ->orderBy('nombre')
            ->get();

        if ($estado = $request->query('estado')) {
            $clientes = $clientes->filter(
                fn (Cliente $cliente) => $cliente->suscripcionActiva()?->estado === $estado
            );
        }

        return view('clientes.index', [
            'clientes' => $clientes,
            'estadoFiltro' => $estado,
            'busqueda' => $busqueda,
        ]);
    }

    public function create(): View
    {
        return view('clientes.form', [
            'cliente' => new Cliente(),
            'suscripcion' => new Suscripcion(),
            'planes' => Plan::where('activo', true)->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validarCliente($request);

        $cliente = Cliente::create([
            'nombre' => $datos['nombre'],
            'telefono' => $datos['telefono'],
            'correo' => $datos['correo'],
            'direccion' => $datos['direccion'],
        ]);

        $cliente->suscripciones()->create([
            'plan_id' => $datos['plan_id'],
            'dia_pago' => $datos['dia_pago'],
            'fecha_proximo_pago' => Suscripcion::calcularProximaFecha((int) $datos['dia_pago']),
        ]);

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('status', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load(['suscripciones.plan', 'suscripciones.pagos.recibo', 'suscripciones.enlace']);

        return view('clientes.show', [
            'cliente' => $cliente,
            'suscripcion' => $cliente->suscripcionActiva(),
        ]);
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.form', [
            'cliente' => $cliente,
            'suscripcion' => $cliente->suscripcionActiva() ?? new Suscripcion(),
            'planes' => Plan::where('activo', true)->orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $datos = $this->validarCliente($request);

        $cliente->update([
            'nombre' => $datos['nombre'],
            'telefono' => $datos['telefono'],
            'correo' => $datos['correo'],
            'direccion' => $datos['direccion'],
        ]);

        if ($suscripcion = $cliente->suscripcionActiva()) {
            $cambioDia = (int) $datos['dia_pago'] !== $suscripcion->dia_pago;

            $suscripcion->update([
                'plan_id' => $datos['plan_id'],
                'dia_pago' => $datos['dia_pago'],
                'fecha_proximo_pago' => $cambioDia
                    ? Suscripcion::calcularProximaFecha((int) $datos['dia_pago'])
                    : $suscripcion->fecha_proximo_pago,
            ]);
        }

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('status', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('status', 'Cliente eliminado.');
    }

    public function toggleActivo(Cliente $cliente): RedirectResponse
    {
        $cliente->update(['activo' => ! $cliente->activo]);

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('status', $cliente->activo ? 'Cliente reactivado.' : 'Cliente dado de baja.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validarCliente(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'correo' => ['nullable', 'email', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'plan_id' => ['required', 'exists:planes,id'],
            'dia_pago' => ['required', 'integer', 'min:1', 'max:31'],
        ]);
    }
}

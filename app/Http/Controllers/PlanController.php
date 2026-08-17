<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        return view('planes.index', [
            'planes' => Plan::withCount('suscripciones')->orderBy('nombre')->get(),
        ]);
    }

    public function create(): View
    {
        return view('planes.form', ['plan' => new Plan()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Plan::create($this->validarPlan($request));

        return redirect()->route('planes.index')->with('status', 'Oferta creada correctamente.');
    }

    public function edit(Plan $plan): View
    {
        return view('planes.form', ['plan' => $plan]);
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $plan->update($this->validarPlan($request));

        return redirect()->route('planes.index')->with('status', 'Oferta actualizada correctamente.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('planes.index')->with('status', 'Oferta eliminada.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validarPlan(Request $request): array
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'velocidad_mbps' => ['required', 'integer', 'min:1'],
            'precio' => ['required', 'numeric', 'min:0'],
            'descripcion' => ['nullable', 'string'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $datos['activo'] = $request->boolean('activo');

        return $datos;
    }
}

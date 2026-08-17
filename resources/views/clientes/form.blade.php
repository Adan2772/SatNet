@php $editando = $cliente->exists; @endphp

<x-app-layout :title="$editando ? 'Editar cliente' : 'Nuevo cliente'">
    <h1 class="font-display text-2xl font-bold">{{ $editando ? 'Editar cliente' : 'Nuevo cliente' }}</h1>

    <form method="POST"
          action="{{ $editando ? route('clientes.update', $cliente) : route('clientes.store') }}"
          class="mt-6 max-w-lg space-y-5">
        @csrf
        @if ($editando) @method('PUT') @endif

        <div>
            <label for="nombre" class="mb-1 block text-sm font-medium text-ink/80">Nombre</label>
            <input id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required
                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            @error('nombre') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="telefono" class="mb-1 block text-sm font-medium text-ink/80">Teléfono</label>
                <input id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>
            <div>
                <label for="correo" class="mb-1 block text-sm font-medium text-ink/80">Correo</label>
                <input id="correo" type="email" name="correo" value="{{ old('correo', $cliente->correo) }}"
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                @error('correo') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="direccion" class="mb-1 block text-sm font-medium text-ink/80">Dirección</label>
            <input id="direccion" name="direccion" value="{{ old('direccion', $cliente->direccion) }}"
                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
        </div>

        <div class="grid grid-cols-2 gap-4 border-t border-brand-100 pt-5">
            <div>
                <label for="plan_id" class="mb-1 block text-sm font-medium text-ink/80">Oferta de internet</label>
                <select id="plan_id" name="plan_id" required
                        class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                    @foreach ($planes as $plan)
                        <option value="{{ $plan->id }}" @selected(old('plan_id', $suscripcion->plan_id) == $plan->id)>
                            {{ $plan->nombre }} — {{ $plan->velocidad_mbps }} Mb — ${{ number_format($plan->precio, 2) }}
                        </option>
                    @endforeach
                </select>
                @error('plan_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="dia_pago" class="mb-1 block text-sm font-medium text-ink/80">Día de pago (1–31)</label>
                <input id="dia_pago" type="number" min="1" max="31" name="dia_pago"
                       value="{{ old('dia_pago', $suscripcion->dia_pago ?? 1) }}" required
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                @error('dia_pago') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">
                {{ $editando ? 'Guardar cambios' : 'Crear cliente' }}
            </button>
            <a href="{{ $editando ? route('clientes.show', $cliente) : route('clientes.index') }}" class="text-sm text-ink/60 hover:text-ink">
                Cancelar
            </a>
        </div>
    </form>
</x-app-layout>

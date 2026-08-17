@php $editando = $enlace->exists; @endphp

<x-app-layout :title="'Enlace de '.$suscripcion->cliente->nombre">
    <h1 class="font-display text-2xl font-bold">{{ $editando ? 'Editar enlace' : 'Datos del enlace' }}</h1>
    <p class="mt-1 text-sm text-ink/60">{{ $suscripcion->cliente->nombre }} — {{ $suscripcion->plan->nombre }}</p>

    <form method="POST" action="{{ route('suscripciones.enlace.update', $suscripcion) }}" class="mt-6 max-w-lg space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="nombre" class="mb-1 block text-sm font-medium text-ink/80">Nombre del enlace</label>
            <input id="nombre" name="nombre" value="{{ old('nombre', $enlace->nombre) }}" required
                   placeholder="ENL-0231"
                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            @error('nombre') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="ip_asignada" class="mb-1 block text-sm font-medium text-ink/80">IP asignada</label>
                <input id="ip_asignada" name="ip_asignada" value="{{ old('ip_asignada', $enlace->ip_asignada) }}"
                       placeholder="10.10.4.23"
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm font-mono focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                @error('ip_asignada') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="mac_address" class="mb-1 block text-sm font-medium text-ink/80">MAC del equipo</label>
                <input id="mac_address" name="mac_address" value="{{ old('mac_address', $enlace->mac_address) }}"
                       placeholder="AA:BB:CC:11:22:33"
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm font-mono focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                @error('mac_address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="tipo_antena" class="mb-1 block text-sm font-medium text-ink/80">Antena / equipo del cliente</label>
                <input id="tipo_antena" name="tipo_antena" value="{{ old('tipo_antena', $enlace->tipo_antena) }}"
                       placeholder="Ubiquiti LiteBeam AC"
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>
            <div>
                <label for="nodo" class="mb-1 block text-sm font-medium text-ink/80">Nodo / torre</label>
                <input id="nodo" name="nodo" value="{{ old('nodo', $enlace->nodo) }}"
                       placeholder="Torre Centro"
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="numero_serie" class="mb-1 block text-sm font-medium text-ink/80">Número de serie</label>
                <input id="numero_serie" name="numero_serie" value="{{ old('numero_serie', $enlace->numero_serie) }}"
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm font-mono focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>
            <div>
                <label for="fecha_instalacion" class="mb-1 block text-sm font-medium text-ink/80">Fecha de instalación</label>
                <input id="fecha_instalacion" type="date" name="fecha_instalacion"
                       value="{{ old('fecha_instalacion', optional($enlace->fecha_instalacion)->format('Y-m-d')) }}" required
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                @error('fecha_instalacion') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="estado" class="mb-1 block text-sm font-medium text-ink/80">Estado del enlace</label>
            <select id="estado" name="estado" required
                    class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                @foreach (['activo' => 'Activo', 'suspendido' => 'Suspendido', 'falla' => 'En falla'] as $valor => $label)
                    <option value="{{ $valor }}" @selected(old('estado', $enlace->estado ?? 'activo') === $valor)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4 border-t border-brand-100 pt-5">
            <div>
                <label for="latitud" class="mb-1 block text-sm font-medium text-ink/80">Latitud (opcional)</label>
                <input id="latitud" name="latitud" value="{{ old('latitud', $enlace->latitud) }}"
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm font-mono focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>
            <div>
                <label for="longitud" class="mb-1 block text-sm font-medium text-ink/80">Longitud (opcional)</label>
                <input id="longitud" name="longitud" value="{{ old('longitud', $enlace->longitud) }}"
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm font-mono focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">
                Guardar enlace
            </button>
            <a href="{{ route('clientes.show', $suscripcion->cliente) }}" class="text-sm text-ink/60 hover:text-ink">Cancelar</a>
        </div>
    </form>
</x-app-layout>

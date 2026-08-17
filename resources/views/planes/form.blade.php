@php $editando = $plan->exists; @endphp

<x-app-layout :title="$editando ? 'Editar oferta' : 'Nueva oferta'">
    <h1 class="font-display text-2xl font-bold">{{ $editando ? 'Editar oferta' : 'Nueva oferta' }}</h1>

    <form method="POST"
          action="{{ $editando ? route('planes.update', $plan) : route('planes.store') }}"
          class="mt-6 max-w-md space-y-5">
        @csrf
        @if ($editando) @method('PUT') @endif

        <div>
            <label for="nombre" class="mb-1 block text-sm font-medium text-ink/80">Nombre</label>
            <input id="nombre" name="nombre" value="{{ old('nombre', $plan->nombre) }}" required
                   placeholder="Plan 20 Mb"
                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            @error('nombre') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="velocidad_mbps" class="mb-1 block text-sm font-medium text-ink/80">Velocidad (Mb)</label>
                <input id="velocidad_mbps" type="number" min="1" name="velocidad_mbps"
                       value="{{ old('velocidad_mbps', $plan->velocidad_mbps) }}" required
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                @error('velocidad_mbps') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="precio" class="mb-1 block text-sm font-medium text-ink/80">Precio mensual</label>
                <input id="precio" type="number" step="0.01" min="0" name="precio"
                       value="{{ old('precio', $plan->precio) }}" required
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                @error('precio') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="descripcion" class="mb-1 block text-sm font-medium text-ink/80">Descripción (opcional)</label>
            <textarea id="descripcion" name="descripcion" rows="3"
                      class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">{{ old('descripcion', $plan->descripcion) }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm text-ink/70">
            <input type="checkbox" name="activo" value="1" @checked(old('activo', $plan->activo ?? true))
                   class="rounded border-brand-200 text-brand-600 focus:ring-brand-300">
            Oferta activa (visible al dar de alta clientes nuevos)
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">
                {{ $editando ? 'Guardar cambios' : 'Crear oferta' }}
            </button>
            <a href="{{ route('planes.index') }}" class="text-sm text-ink/60 hover:text-ink">Cancelar</a>
        </div>
    </form>
</x-app-layout>

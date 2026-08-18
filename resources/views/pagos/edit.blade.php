<x-app-layout :title="'Editar pago'">
    <h1 class="font-display text-2xl font-bold">Editar pago</h1>
    <p class="mt-1 text-sm text-ink/60">{{ $suscripcion->cliente->nombre }} — {{ $pago->fecha_pago->translatedFormat('d M Y') }}</p>

    <form method="POST" action="{{ route('suscripciones.pagos.update', [$suscripcion, $pago]) }}" class="mt-6 max-w-sm space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="monto" class="mb-1 block text-sm font-medium text-ink/80">Monto</label>
            <input id="monto" type="number" step="0.01" min="0" name="monto" value="{{ old('monto', $pago->monto) }}" required
                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            @error('monto') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="notas" class="mb-1 block text-sm font-medium text-ink/80">Notas (opcional)</label>
            <input id="notas" name="notas" value="{{ old('notas', $pago->notas) }}"
                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
        </div>

        <p class="text-xs text-ink/50">
            Corregir el monto no reenvía el recibo ya enviado por correo, pero sí actualiza el PDF si se vuelve a descargar.
        </p>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">
                Guardar cambios
            </button>
            <a href="{{ route('clientes.show', $suscripcion->cliente) }}" class="text-sm text-ink/60 hover:text-ink">Cancelar</a>
        </div>
    </form>
</x-app-layout>

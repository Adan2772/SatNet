<x-app-layout :title="'Reporte de cobros'">
    <h1 class="font-display text-2xl font-bold">Reporte de cobros</h1>
    <p class="mt-1 text-sm text-ink/60">Pagos registrados en el rango de fechas seleccionado.</p>

    <form method="GET" action="{{ route('reportes.pagos') }}" class="mt-6 flex flex-wrap items-end gap-4">
        <div>
            <label for="desde" class="mb-1 block text-xs font-medium text-ink/70">Desde</label>
            <input id="desde" type="date" name="desde" value="{{ $desde->format('Y-m-d') }}"
                   class="rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
        </div>
        <div>
            <label for="hasta" class="mb-1 block text-xs font-medium text-ink/70">Hasta</label>
            <input id="hasta" type="date" name="hasta" value="{{ $hasta->format('Y-m-d') }}"
                   class="rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
        </div>
        <button type="submit" class="rounded-lg border border-brand-100 px-4 py-2 text-sm font-medium hover:bg-brand-50/60">Filtrar</button>
        <a href="{{ route('reportes.pagos.exportar', ['desde' => $desde->format('Y-m-d'), 'hasta' => $hasta->format('Y-m-d')]) }}"
           class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">
            Descargar CSV
        </a>
    </form>

    <div class="mt-6 rounded-xl border border-brand-100 bg-white p-4">
        <div class="flex items-baseline justify-between">
            <span class="text-sm text-ink/50">Total del periodo</span>
            <span class="font-mono text-2xl font-semibold tabular-nums">${{ number_format($total, 2) }}</span>
        </div>
        <p class="mt-1 text-xs text-ink/40">{{ $pagos->count() }} pago(s) entre {{ $desde->translatedFormat('d M Y') }} y {{ $hasta->translatedFormat('d M Y') }}</p>
    </div>

    <div class="mt-5 overflow-hidden rounded-xl border border-brand-100 bg-white">
        @if ($pagos->isEmpty())
            <p class="px-4 py-10 text-center text-sm text-ink/50">No hay pagos registrados en este rango.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-brand-100 bg-brand-50/40 text-left text-xs uppercase tracking-wide text-ink/50">
                        <tr>
                            <th class="px-4 py-2.5 font-medium">Fecha</th>
                            <th class="px-4 py-2.5 font-medium">Cliente</th>
                            <th class="px-4 py-2.5 font-medium">Plan</th>
                            <th class="px-4 py-2.5 font-medium">Recibo</th>
                            <th class="px-4 py-2.5 font-medium text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-100">
                        @foreach ($pagos as $pago)
                            <tr>
                                <td class="px-4 py-3 text-ink/70">{{ $pago->fecha_pago->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('clientes.show', $pago->suscripcion->cliente) }}" class="font-medium hover:text-brand-600">
                                        {{ $pago->suscripcion->cliente->nombre }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-ink/60">{{ $pago->suscripcion->plan->nombre }}</td>
                                <td class="px-4 py-3 font-mono text-xs text-ink/50">{{ $pago->recibo->folio ?? '—' }}</td>
                                <td class="px-4 py-3 text-right font-mono tabular-nums">${{ number_format($pago->monto, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>

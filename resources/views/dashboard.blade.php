<x-app-layout :title="'Dashboard'">
    <h1 class="font-display text-2xl font-bold">Dashboard</h1>
    <p class="mt-1 text-sm text-ink/60">Resumen del mes — {{ now()->translatedFormat('F Y') }}</p>

    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-brand-100 bg-white p-4">
            <div class="text-2xl font-semibold tabular-nums">{{ $alDia }}</div>
            <div class="mt-1 text-xs uppercase tracking-wide text-ink/50">Clientes al día</div>
        </div>
        <div class="rounded-xl border border-brand-100 bg-white p-4">
            <div class="text-2xl font-semibold tabular-nums">{{ $enTolerancia }}</div>
            <div class="mt-1 text-xs uppercase tracking-wide text-ink/50">En tolerancia</div>
        </div>
        <div class="rounded-xl border border-brand-100 bg-white p-4">
            <div class="text-2xl font-semibold tabular-nums">{{ $vencidos }}</div>
            <div class="mt-1 text-xs uppercase tracking-wide text-ink/50">Vencidos</div>
        </div>
        <div class="rounded-xl border border-brand-100 bg-white p-4">
            <div class="text-2xl font-semibold tabular-nums">${{ number_format($cobradoEsteMes, 2) }}</div>
            <div class="mt-1 text-xs uppercase tracking-wide text-ink/50">Cobrado este mes</div>
        </div>
    </div>

    <div class="mt-8">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="font-display text-lg font-semibold">Próximos a atender</h2>
            <a href="{{ route('clientes.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Ver todos los clientes →</a>
        </div>

        @if ($proximosVencimientos->isEmpty())
            <p class="rounded-xl border border-dashed border-brand-200 bg-white px-4 py-6 text-center text-sm text-ink/50">
                No hay clientes en tolerancia o vencidos ahora mismo.
            </p>
        @else
            <div class="overflow-hidden rounded-xl border border-brand-100 bg-white">
                <table class="w-full text-sm">
                    <tbody class="divide-y divide-brand-100">
                        @foreach ($proximosVencimientos as $suscripcion)
                            <tr>
                                <td class="px-4 py-3"><x-pill :estado="$suscripcion->estado" /></td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('clientes.show', $suscripcion->cliente) }}" class="font-medium hover:text-brand-600">
                                        {{ $suscripcion->cliente->nombre }}
                                    </a>
                                    <span class="text-ink/50">— {{ $suscripcion->plan->nombre }}</span>
                                </td>
                                <td class="px-4 py-3 text-right font-mono text-xs text-ink/50">
                                    vence {{ $suscripcion->fecha_proximo_pago->translatedFormat('d M') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-8">
        <div class="mb-3 flex items-center justify-between">
            <h2 class="font-display text-lg font-semibold">Ingresos — últimos 6 meses</h2>
            <a href="{{ route('reportes.pagos') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Ver reporte de cobros →</a>
        </div>
        <div class="rounded-xl border border-brand-100 bg-white p-4">
            @php $maximo = max(1, $ingresosPorMes->max('total')); @endphp
            <div class="space-y-3">
                @foreach ($ingresosPorMes as $mes)
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-16 shrink-0 text-ink/50">{{ $mes['etiqueta'] }}</span>
                        <div class="h-2.5 flex-1 rounded-full bg-accent-soft">
                            <div class="h-2.5 rounded-full bg-accent" style="width: {{ max(3, round($mes['total'] / $maximo * 100)) }}%"></div>
                        </div>
                        <span class="w-24 shrink-0 text-right font-mono tabular-nums">${{ number_format($mes['total'], 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

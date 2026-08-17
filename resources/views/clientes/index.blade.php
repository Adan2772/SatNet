<x-app-layout :title="'Clientes'">
    <div class="flex items-center justify-between">
        <h1 class="font-display text-2xl font-bold">Clientes</h1>
        <a href="{{ route('clientes.create') }}"
           class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">
            + Nuevo cliente
        </a>
    </div>

    <div class="mt-5 flex gap-2 text-sm">
        @foreach (['' => 'Todos', 'al_dia' => 'Al día', 'tolerancia' => 'Tolerancia', 'vencido' => 'Vencido'] as $valor => $label)
            <a href="{{ route('clientes.index', $valor ? ['estado' => $valor] : []) }}"
               class="rounded-full border px-3 py-1.5 {{ $estadoFiltro === $valor || (!$estadoFiltro && !$valor) ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-brand-100 text-ink/60 hover:bg-brand-50/60' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="mt-5 overflow-hidden rounded-xl border border-brand-100 bg-white">
        @if ($clientes->isEmpty())
            <p class="px-4 py-10 text-center text-sm text-ink/50">Ningún cliente coincide con este filtro todavía.</p>
        @else
            <table class="w-full text-sm">
                <thead class="border-b border-brand-100 bg-brand-50/40 text-left text-xs uppercase tracking-wide text-ink/50">
                    <tr>
                        <th class="px-4 py-2.5 font-medium">Estado</th>
                        <th class="px-4 py-2.5 font-medium">Cliente</th>
                        <th class="px-4 py-2.5 font-medium">Plan</th>
                        <th class="px-4 py-2.5 font-medium text-right">Próximo pago</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-100">
                    @foreach ($clientes as $cliente)
                        @php $suscripcion = $cliente->suscripcionActiva(); @endphp
                        <tr class="cursor-pointer hover:bg-brand-50/30" onclick="window.location='{{ route('clientes.show', $cliente) }}'">
                            <td class="px-4 py-3">
                                @if ($suscripcion)
                                    <x-pill :estado="$suscripcion->estado" />
                                @else
                                    <span class="text-xs text-ink/40">sin plan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium">{{ $cliente->nombre }}</td>
                            <td class="px-4 py-3 text-ink/60">{{ $suscripcion?->plan?->nombre ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-mono text-xs text-ink/50">
                                {{ $suscripcion?->fecha_proximo_pago?->translatedFormat('d M Y') ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>

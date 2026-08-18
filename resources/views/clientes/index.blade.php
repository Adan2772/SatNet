<x-app-layout :title="'Clientes'">
    <div class="flex items-center justify-between">
        <h1 class="font-display text-2xl font-bold">Clientes</h1>
        <a href="{{ route('clientes.create') }}"
           class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">
            + Nuevo cliente
        </a>
    </div>

    <form method="GET" action="{{ route('clientes.index') }}" class="mt-5 flex gap-2">
        @if ($estadoFiltro)
            <input type="hidden" name="estado" value="{{ $estadoFiltro }}">
        @endif
        <input type="text" name="q" value="{{ $busqueda }}" placeholder="Buscar por nombre, teléfono o correo…"
               class="w-full max-w-sm rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
        <button type="submit" class="rounded-lg border border-brand-100 px-3 py-2 text-sm font-medium hover:bg-brand-50/60">Buscar</button>
        @if ($busqueda !== '')
            <a href="{{ route('clientes.index', $estadoFiltro ? ['estado' => $estadoFiltro] : []) }}"
               class="rounded-lg px-3 py-2 text-sm text-ink/50 hover:text-ink">Limpiar</a>
        @endif
    </form>

    <div class="mt-4 flex gap-2 text-sm">
        @foreach (['' => 'Todos', 'al_dia' => 'Al día', 'tolerancia' => 'Tolerancia', 'vencido' => 'Vencido'] as $valor => $label)
            <a href="{{ route('clientes.index', array_filter(['estado' => $valor ?: null, 'q' => $busqueda ?: null])) }}"
               class="rounded-full border px-3 py-1.5 {{ $estadoFiltro === $valor || (!$estadoFiltro && !$valor) ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-brand-100 text-ink/60 hover:bg-brand-50/60' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="mt-5 overflow-hidden rounded-xl border border-brand-100 bg-white">
        @if ($clientes->isEmpty())
            <p class="px-4 py-10 text-center text-sm text-ink/50">Ningún cliente coincide con esta búsqueda.</p>
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
                        <tr class="cursor-pointer hover:bg-brand-50/30 {{ $cliente->activo ? '' : 'opacity-50' }}" onclick="window.location='{{ route('clientes.show', $cliente) }}'">
                            <td class="px-4 py-3">
                                @if (! $cliente->activo)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-500">Inactivo</span>
                                @elseif ($suscripcion)
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

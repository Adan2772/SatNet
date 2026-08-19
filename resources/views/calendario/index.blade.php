<x-app-layout :title="'Calendario de cobros'">
    <h1 class="font-display text-2xl font-bold">Calendario de cobros</h1>
    <p class="mt-1 text-sm text-ink/60">
        Clientes activos agrupados por su día de pago del mes — se repite cada mes, no es una fecha fija.
        {{ $total }} cliente(s) con suscripción activa.
    </p>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        @foreach ($grupos as $titulo => $porDia)
            <div>
                <div class="mb-3 flex items-center justify-between">
                    <h2 class="font-display text-base font-semibold">{{ $titulo }}</h2>
                    <span class="font-mono text-xs text-ink/40">{{ $porDia->flatten()->count() }} cliente(s)</span>
                </div>

                <div class="space-y-3">
                    @forelse ($porDia as $dia => $suscripciones)
                        <div class="rounded-xl border border-brand-100 bg-white p-4">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="font-mono text-sm font-semibold">Día {{ $dia }}</span>
                                <span class="text-xs text-ink/40">{{ $suscripciones->count() }} cliente(s)</span>
                            </div>
                            <ul class="space-y-1.5">
                                @foreach ($suscripciones as $suscripcion)
                                    <li class="flex items-center justify-between gap-2 text-sm">
                                        <a href="{{ route('clientes.show', $suscripcion->cliente) }}" class="truncate font-medium hover:text-brand-600">
                                            {{ $suscripcion->cliente->nombre }}
                                        </a>
                                        <x-pill :estado="$suscripcion->estado" />
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-brand-200 bg-white px-4 py-6 text-center text-sm text-ink/40">
                            Sin clientes en este rango.
                        </p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>

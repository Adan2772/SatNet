<x-app-layout :title="'Ofertas'">
    <div class="flex items-center justify-between">
        <h1 class="font-display text-2xl font-bold">Ofertas de internet</h1>
        <a href="{{ route('planes.create') }}"
           class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">
            + Nueva oferta
        </a>
    </div>

    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($planes as $plan)
            <div class="rounded-xl border border-brand-100 bg-white p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="font-display text-base font-semibold">{{ $plan->nombre }}</h2>
                        <p class="text-sm text-ink/50">{{ $plan->velocidad_mbps }} Mb</p>
                    </div>
                    @unless ($plan->activo)
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500">inactiva</span>
                    @endunless
                </div>
                <p class="mt-3 font-mono text-xl tabular-nums">${{ number_format($plan->precio, 2) }}<span class="text-sm text-ink/40">/mes</span></p>
                @if ($plan->descripcion)
                    <p class="mt-2 text-sm text-ink/60">{{ $plan->descripcion }}</p>
                @endif
                <div class="mt-4 flex items-center justify-between border-t border-brand-100 pt-3 text-sm">
                    <span class="text-ink/40">{{ $plan->suscripciones_count }} cliente(s)</span>
                    <a href="{{ route('planes.edit', $plan) }}" class="font-medium text-brand-600 hover:text-brand-700">Editar</a>
                </div>
            </div>
        @empty
            <p class="col-span-full rounded-xl border border-dashed border-brand-200 bg-white px-4 py-10 text-center text-sm text-ink/50">
                Todavía no hay ofertas creadas.
            </p>
        @endforelse
    </div>
</x-app-layout>

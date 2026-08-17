<x-app-layout :title="$cliente->nombre">
    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="font-display text-2xl font-bold">{{ $cliente->nombre }}</h1>
                @if ($suscripcion)
                    <x-pill :estado="$suscripcion->estado" />
                @endif
            </div>
            <p class="mt-1 text-sm text-ink/60">
                {{ $cliente->correo ?? 'sin correo' }} · {{ $cliente->telefono ?? 'sin teléfono' }}
            </p>
        </div>
        <div class="flex gap-2 text-sm">
            <a href="{{ route('clientes.edit', $cliente) }}" class="rounded-lg border border-brand-100 px-3 py-2 font-medium hover:bg-brand-50/60">Editar</a>
            <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" onsubmit="return confirm('¿Eliminar a {{ $cliente->nombre }}? Esta acción no se puede deshacer.');">
                @csrf @method('DELETE')
                <button class="rounded-lg border border-rose-200 px-3 py-2 font-medium text-rose-600 hover:bg-rose-50">Eliminar</button>
            </form>
        </div>
    </div>

    @if ($suscripcion)
        <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_320px]">
            <div>
                <h2 class="mb-3 font-display text-lg font-semibold">Historial de pagos</h2>
                <div class="overflow-hidden rounded-xl border border-brand-100 bg-white">
                    @if ($suscripcion->pagos->isEmpty())
                        <p class="px-4 py-8 text-center text-sm text-ink/50">Todavía no hay pagos registrados.</p>
                    @else
                        <table class="w-full text-sm">
                            <thead class="border-b border-brand-100 bg-brand-50/40 text-left text-xs uppercase tracking-wide text-ink/50">
                                <tr>
                                    <th class="px-4 py-2.5 font-medium">Fecha</th>
                                    <th class="px-4 py-2.5 font-medium">Monto</th>
                                    <th class="px-4 py-2.5 font-medium">Recibo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-100">
                                @foreach ($suscripcion->pagos->sortByDesc('fecha_pago') as $pago)
                                    <tr>
                                        <td class="px-4 py-3 text-ink/70">{{ $pago->fecha_pago->translatedFormat('d M Y') }}</td>
                                        <td class="px-4 py-3 font-mono tabular-nums">${{ number_format($pago->monto, 2) }}</td>
                                        <td class="px-4 py-3">
                                            @if ($pago->recibo)
                                                <a href="{{ route('recibos.show', $pago->recibo) }}" target="_blank"
                                                   class="font-mono text-xs text-brand-600 hover:text-brand-700">
                                                    {{ $pago->recibo->folio }} ↗
                                                </a>
                                            @else
                                                <span class="text-xs text-ink/40">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <div>
                <h2 class="mb-3 font-display text-lg font-semibold">Registrar pago</h2>
                <div class="rounded-xl border border-brand-100 bg-white p-4">
                    <dl class="mb-4 space-y-1 text-sm">
                        <div class="flex justify-between"><dt class="text-ink/50">Plan</dt><dd class="font-medium">{{ $suscripcion->plan->nombre }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink/50">Precio</dt><dd class="font-mono">${{ number_format($suscripcion->plan->precio, 2) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink/50">Próximo pago</dt><dd class="font-mono">{{ $suscripcion->fecha_proximo_pago->translatedFormat('d M Y') }}</dd></div>
                    </dl>
                    <form method="POST" action="{{ route('suscripciones.pagos.store', $suscripcion) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label for="monto" class="mb-1 block text-xs font-medium text-ink/70">Monto</label>
                            <input id="monto" type="number" step="0.01" min="0" name="monto"
                                   value="{{ $suscripcion->plan->precio }}" required
                                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                        </div>
                        <div>
                            <label for="notas" class="mb-1 block text-xs font-medium text-ink/70">Notas (opcional)</label>
                            <input id="notas" name="notas"
                                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">
                            Registrar pago y enviar recibo
                        </button>
                    </form>
                </div>

                <div class="mt-6">
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="font-display text-lg font-semibold">Enlace técnico</h2>
                        <a href="{{ route('suscripciones.enlace.edit', $suscripcion) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">
                            {{ $suscripcion->enlace ? 'Editar' : '+ Agregar' }}
                        </a>
                    </div>
                    <div class="rounded-xl border border-brand-100 bg-white p-4">
                        @if ($suscripcion->enlace)
                            <div class="mb-3 flex items-center justify-between">
                                <span class="font-mono text-sm font-medium">{{ $suscripcion->enlace->nombre }}</span>
                                <x-pill :estado="$suscripcion->enlace->estado" />
                            </div>
                            <dl class="space-y-1 text-sm">
                                <div class="flex justify-between"><dt class="text-ink/50">IP asignada</dt><dd class="font-mono">{{ $suscripcion->enlace->ip_asignada ?? '—' }}</dd></div>
                                <div class="flex justify-between"><dt class="text-ink/50">Antena / equipo</dt><dd>{{ $suscripcion->enlace->tipo_antena ?? '—' }}</dd></div>
                                <div class="flex justify-between"><dt class="text-ink/50">Nodo</dt><dd>{{ $suscripcion->enlace->nodo ?? '—' }}</dd></div>
                                <div class="flex justify-between"><dt class="text-ink/50">Instalado</dt><dd class="font-mono">{{ $suscripcion->enlace->fecha_instalacion->translatedFormat('d M Y') }}</dd></div>
                            </dl>
                        @else
                            <p class="text-sm text-ink/50">Todavía no se registró la información técnica de este enlace (IP, antena, nodo, fecha de instalación).</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="mt-6 rounded-xl border border-dashed border-brand-200 bg-white px-4 py-8 text-center text-sm text-ink/50">
            Este cliente no tiene una suscripción activa.
        </p>
    @endif
</x-app-layout>

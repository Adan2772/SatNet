@props(['estado'])

@php
    $estilos = [
        'al_dia' => ['label' => 'Al día', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
        'tolerancia' => ['label' => 'En tolerancia', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
        'vencido' => ['label' => 'Vencido', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
    ][$estado] ?? ['label' => $estado, 'class' => 'bg-gray-50 text-gray-600 border-gray-200'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium {$estilos['class']}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    {{ $estilos['label'] }}
</span>

<x-app-layout :title="'Usuarios'">
    <div class="flex items-center justify-between">
        <h1 class="font-display text-2xl font-bold">Usuarios</h1>
        <a href="{{ route('usuarios.create') }}"
           class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">
            + Nuevo usuario
        </a>
    </div>
    <p class="mt-1 text-sm text-ink/60">Cuentas con acceso al panel de administración.</p>

    <div class="mt-5 overflow-hidden rounded-xl border border-brand-100 bg-white">
        <table class="w-full text-sm">
            <thead class="border-b border-brand-100 bg-brand-50/40 text-left text-xs uppercase tracking-wide text-ink/50">
                <tr>
                    <th class="px-4 py-2.5 font-medium">Nombre</th>
                    <th class="px-4 py-2.5 font-medium">Correo</th>
                    <th class="px-4 py-2.5 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-100">
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td class="px-4 py-3 font-medium">
                            {{ $usuario->name }}
                            @if ($usuario->id === auth()->id())
                                <span class="ml-1.5 rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-700">tú</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-ink/60">{{ $usuario->email }}</td>
                        <td class="px-4 py-3 text-right text-xs">
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="font-medium text-brand-600 hover:text-brand-700">Editar</a>
                            <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" class="inline"
                                  onsubmit="return confirm('¿Eliminar a {{ $usuario->name }}?');">
                                @csrf @method('DELETE')
                                <button class="ml-3 font-medium text-rose-600 hover:text-rose-700">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>

@php $editando = $usuario->exists; @endphp

<x-app-layout :title="$editando ? 'Editar usuario' : 'Nuevo usuario'">
    <h1 class="font-display text-2xl font-bold">{{ $editando ? 'Editar usuario' : 'Nuevo usuario' }}</h1>

    <form method="POST"
          action="{{ $editando ? route('usuarios.update', $usuario) : route('usuarios.store') }}"
          class="mt-6 max-w-sm space-y-5">
        @csrf
        @if ($editando) @method('PUT') @endif

        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-ink/80">Nombre</label>
            <input id="name" name="name" value="{{ old('name', $usuario->name) }}" required
                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-ink/80">Correo</label>
            <input id="email" type="email" name="email" value="{{ old('email', $usuario->email) }}" required
                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-ink/80">
                {{ $editando ? 'Nueva contraseña (opcional)' : 'Contraseña' }}
            </label>
            <input id="password" type="password" name="password" {{ $editando ? '' : 'required' }}
                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            @if ($editando)
                <p class="mt-1 text-xs text-ink/40">Déjala en blanco para mantener la contraseña actual.</p>
            @endif
        </div>

        <div>
            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-ink/80">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" {{ $editando ? '' : 'required' }}
                   class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">
                {{ $editando ? 'Guardar cambios' : 'Crear usuario' }}
            </button>
            <a href="{{ route('usuarios.index') }}" class="text-sm text-ink/60 hover:text-ink">Cancelar</a>
        </div>
    </form>
</x-app-layout>

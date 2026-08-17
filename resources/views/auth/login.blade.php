<!doctype html>
<html lang="es" class="h-full bg-paper">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@700;800&family=IBM+Plex+Sans:wght@400;500;600&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-full items-center justify-center font-sans text-ink antialiased">
    <div class="w-full max-w-sm px-6">
        <div class="mb-8 flex items-center gap-2">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-500 font-display text-base font-bold text-white">S</span>
            <span class="font-display text-xl font-bold tracking-tight">SATNET</span>
        </div>

        <h1 class="mb-1 font-display text-2xl font-bold">Panel de administrador</h1>
        <p class="mb-6 text-sm text-ink/60">Entra para ver el estado de cobro de tus clientes.</p>

        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-ink/80">Correo</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-medium text-ink/80">Contraseña</label>
                <input id="password" type="password" name="password" required
                       class="w-full rounded-lg border border-brand-100 px-3 py-2 text-sm focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>
            <label class="flex items-center gap-2 text-sm text-ink/70">
                <input type="checkbox" name="recordar" class="rounded border-brand-200 text-brand-600 focus:ring-brand-300">
                Recordarme
            </label>
            <button type="submit"
                    class="w-full rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">
                Entrar
            </button>
        </form>
    </div>
</body>
</html>

<!doctype html>
<html lang="es" class="h-full bg-paper">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Panel' }} — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&family=IBM+Plex+Sans:wght@400;500;600&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-ink antialiased">
    <div class="min-h-full lg:grid lg:grid-cols-[220px_1fr]">
        <aside class="border-b border-brand-100 bg-white lg:min-h-full lg:border-b-0 lg:border-r">
            <div class="flex items-center gap-2 px-5 py-5">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-500 font-display text-sm font-bold text-white">S</span>
                <span class="font-display text-lg font-bold tracking-tight">SATNET</span>
            </div>
            <nav class="flex gap-1 overflow-x-auto px-3 pb-3 text-sm font-medium lg:flex-col lg:overflow-visible lg:pb-5">
                @php
                    $links = [
                        ['route' => 'dashboard', 'label' => 'Dashboard'],
                        ['route' => 'clientes.index', 'label' => 'Clientes'],
                        ['route' => 'planes.index', 'label' => 'Ofertas'],
                        ['route' => 'reportes.pagos', 'label' => 'Reportes'],
                    ];
                @endphp
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="whitespace-nowrap rounded-lg px-3 py-2 {{ request()->routeIs($link['route'].'*') ? 'bg-brand-50 text-brand-700' : 'text-ink/70 hover:bg-brand-50/60 hover:text-ink' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
            @auth
                <form method="POST" action="{{ route('logout') }}" class="hidden border-t border-brand-100 px-3 py-3 lg:block">
                    @csrf
                    <button class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-ink/60 hover:bg-brand-50/60 hover:text-ink">
                        Cerrar sesión — {{ auth()->user()->name }}
                    </button>
                </form>
            @endauth
        </aside>

        <main class="px-5 py-8 sm:px-8 lg:px-10">
            <div class="mx-auto max-w-5xl">
                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-brand-100 bg-brand-50 px-4 py-3 text-sm text-brand-700">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>

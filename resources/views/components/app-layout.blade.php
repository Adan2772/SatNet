<!doctype html>
<html lang="es" class="h-full bg-paper">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Panel' }} — {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/satnet-icon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&family=IBM+Plex+Sans:wght@400;500;600&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-ink antialiased">
    <div class="min-h-full lg:grid lg:grid-cols-[220px_1fr]">
        <div class="flex items-center justify-between bg-brand-800 px-4 py-3 lg:hidden">
            <span class="font-display text-base font-semibold text-white">{{ $title ?? config('app.name') }}</span>
            <button type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-nav" aria-label="Abrir menú"
                    class="rounded-lg p-2 text-white/80 hover:bg-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div data-menu-backdrop class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden"></div>

        <aside id="mobile-nav"
               class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full bg-brand-800 transition-transform duration-200 ease-in-out lg:static lg:z-auto lg:w-auto lg:translate-x-0 lg:min-h-full lg:border-r lg:border-white/10">
            <div class="flex items-center justify-between px-5 py-4">
                <img src="{{ asset('images/satnet-logo-dark.svg') }}" alt="SATNET — internet satelital" class="h-16 w-auto">
                <button type="button" data-menu-close aria-label="Cerrar menú" class="rounded-lg p-1.5 text-white/70 hover:bg-white/10 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex flex-col gap-1 px-3 pb-3 text-sm font-medium lg:pb-5">
                @php
                    $links = [
                        ['route' => 'dashboard', 'label' => 'Dashboard'],
                        ['route' => 'calendario', 'label' => 'Calendario'],
                        ['route' => 'clientes.index', 'label' => 'Clientes'],
                        ['route' => 'planes.index', 'label' => 'Ofertas'],
                        ['route' => 'reportes.pagos', 'label' => 'Reportes'],
                        ['route' => 'usuarios.index', 'label' => 'Usuarios'],
                    ];
                @endphp
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="whitespace-nowrap rounded-lg px-3 py-2 {{ request()->routeIs($link['route'].'*') ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
            @auth
                <form method="POST" action="{{ route('logout') }}" class="border-t border-white/10 px-3 py-3">
                    @csrf
                    <button class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-white/60 hover:bg-white/5 hover:text-white">
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

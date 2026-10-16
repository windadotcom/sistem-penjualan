@php $cartCount = array_sum(session('cart', [])); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($pageTitle) ? $pageTitle . ' — ' : '' }}{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen flex flex-col" style="background:#f0fdf8;">

    {{-- ── Top Bar ──────────────────────────────────── --}}
    <header class="sticky top-0 z-50 bg-white border-b border-gray-100" style="box-shadow:0 1px 3px 0 rgb(0 0 0/.07)">
        <div class="max-w-6xl mx-auto px-4 py-0">
            <div class="flex items-center justify-between h-16 gap-4">

                {{-- Logo --}}
                <a href="{{ route('shop.index') }}" class="flex items-center gap-2.5 shrink-0">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="font-extrabold text-gray-800 text-base tracking-tight">{{ config('app.name') }}</span>
                </a>

                {{-- Search bar (hidden on mobile) --}}
                <form method="GET" action="{{ route('shop.index') }}" class="hidden md:flex flex-1 max-w-sm">
                    <div class="relative w-full">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Cari produk..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 focus:outline-none transition">
                    </div>
                </form>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('cart.index') }}"
                       class="relative flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="hidden sm:inline">Keranjang</span>
                        @if ($cartCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                                {{ $cartCount > 9 ? '9+' : $cartCount }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl px-3 py-2 transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl px-3 py-2 transition">
                            Login Staf
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- ── Main Content ─────────────────────────────── --}}
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-6 space-y-5">
        @include('partials.flash')
        {{ $slot }}
    </main>

    {{-- ── Footer ───────────────────────────────────── --}}
    <footer class="mt-auto border-t border-gray-200 bg-white">
        <div class="max-w-6xl mx-auto px-4 py-5 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-400">
            <span>© {{ date('Y') }} <b class="text-gray-600">{{ config('app.name') }}</b>. All rights reserved.</span>
            <div class="flex items-center gap-4">
                <a href="{{ route('shop.index') }}" class="hover:text-emerald-600 transition">Toko</a>
                <a href="{{ route('cart.index') }}" class="hover:text-emerald-600 transition">Keranjang</a>
                @guest
                    <a href="{{ route('login') }}" class="hover:text-emerald-600 transition">Login</a>
                @endguest
            </div>
        </div>
    </footer>

</body>
</html>

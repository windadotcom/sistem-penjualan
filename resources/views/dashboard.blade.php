<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-heading">Dashboard</h1>
            <p class="text-xs text-gray-500 mt-0.5">Selamat datang, {{ Auth::user()->name }} 👋</p>
        </div>
        <span class="text-xs text-gray-400 bg-gray-100 rounded-lg px-3 py-1.5 font-medium">
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ── Notifikasi Pesanan Baru ─────────────────────── --}}
        @if ($newOrders > 0)
            <a href="{{ route('orders.index', ['status' => 'baru']) }}"
               class="flex items-center gap-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-5 py-3.5 font-semibold text-sm hover:bg-amber-100 transition group">
                <span class="text-xl animate-bounce">🔔</span>
                <span>Ada <b>{{ $newOrders }} pesanan online</b> baru menunggu diproses</span>
                <svg class="w-4 h-4 ms-auto text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif

        {{-- ── Stat Cards ──────────────────────────────────── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Pendapatan --}}
            <div class="stat-card border-l-4 border-blue-500">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pendapatan</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-1 leading-none">
                            Rp {{ number_format($revenue, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-blue-600 font-medium mt-2">Semua transaksi</p>
            </div>

            {{-- Transaksi --}}
            <div class="stat-card border-l-4 border-sky-500">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transaksi</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-1 leading-none">{{ $transactions }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-sky-600 font-medium mt-2">Total penjualan</p>
            </div>

            {{-- Barang Terjual --}}
            <div class="stat-card border-l-4 border-violet-500">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Barang Terjual</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-1 leading-none">{{ $sold }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-violet-600 font-medium mt-2">Unit terjual</p>
            </div>

            {{-- Jenis Barang --}}
            <div class="stat-card border-l-4 border-orange-400">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis Barang</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-1 leading-none">{{ $productCount }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-orange-500 font-medium mt-2">Produk terdaftar</p>
            </div>
        </div>

        {{-- ── Quick Actions + Stok Menipis ───────────────── --}}
        <div class="grid lg:grid-cols-3 gap-5">

            {{-- Quick Actions --}}
            <div class="card lg:col-span-1 space-y-3">
                <h2 class="font-bold text-gray-800 text-sm uppercase tracking-wider">Akses Cepat</h2>
                <a href="{{ route('kasir.create') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-sm hover:from-blue-700 hover:to-indigo-700 transition shadow-sm">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    Buka Kasir
                    <svg class="w-4 h-4 ms-auto opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('sales.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold text-sm border border-gray-200 transition">
                    <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    Riwayat Transaksi
                    <svg class="w-4 h-4 ms-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                @if (Auth::user()->isAdmin())
                <a href="{{ route('products.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold text-sm border border-gray-200 transition">
                    <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    Kelola Barang
                    <svg class="w-4 h-4 ms-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold text-sm border border-gray-200 transition">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    Laporan
                    <svg class="w-4 h-4 ms-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif
            </div>

            {{-- Stok Menipis --}}
            <div class="card lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h2 class="font-bold text-gray-800 text-sm">Stok Menipis <span class="text-gray-400 font-normal">(≤ 5)</span></h2>
                    </div>
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('products.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">Kelola Stok →</a>
                    @endif
                </div>

                @forelse ($low as $p)
                    <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-sm">📦</div>
                            <span class="text-sm font-medium text-gray-700">{{ $p->name }}</span>
                        </div>
                        <span class="badge {{ $p->stock === 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $p->stock === 0 ? 'Habis' : 'Sisa ' . $p->stock }}
                        </span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="text-3xl mb-2">✅</div>
                        <p class="text-sm font-semibold text-gray-600">Semua stok aman</p>
                        <p class="text-xs text-gray-400 mt-1">Tidak ada produk dengan stok rendah</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-heading">Laporan Penjualan</h1>
            <p class="text-xs text-gray-500 mt-0.5">Rekap transaksi berdasarkan rentang tanggal</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('reports.pdf', request()->query()) }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh PDF
            </a>
            <a href="{{ route('reports.excel', request()->query()) }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh Excel
            </a>
            <a href="{{ route('reports.csv', request()->query()) }}" class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh CSV
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

        {{-- ── Filter Form ─────────────────────────────── --}}
        <div class="card">
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="from" class="block text-xs font-semibold text-gray-600 mb-1.5">Dari Tanggal</label>
                    <input id="from" type="date" name="from" value="{{ request('from') }}"
                           class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                </div>
                <div>
                    <label for="to" class="block text-xs font-semibold text-gray-600 mb-1.5">Sampai Tanggal</label>
                    <input id="to" type="date" name="to" value="{{ request('to') }}" min="{{ request('from') }}"
                           class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filter
                </button>
                @if (request('from') || request('to'))
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>
            @if ($errors->any())
                <p class="text-sm text-red-600 mt-3">{{ $errors->first() }}</p>
            @endif
        </div>

        {{-- ── Summary Card ─────────────────────────────── --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="stat-card border-l-4 border-blue-500">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pendapatan</p>
                        <p class="text-xl font-extrabold text-gray-900 mt-1 leading-none">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-blue-600 font-medium mt-2">
                    @if (request('from') || request('to'))
                        {{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('d/m/Y') : 'Awal' }} – {{ request('to') ? \Carbon\Carbon::parse(request('to'))->format('d/m/Y') : 'Sekarang' }}
                    @else
                        Semua waktu
                    @endif
                </p>
            </div>

            <div class="stat-card border-l-4 border-sky-500">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Transaksi</p>
                        <p class="text-xl font-extrabold text-gray-900 mt-1 leading-none">{{ $sales->total() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-sky-600 font-medium mt-2">Transaksi tercatat</p>
            </div>

            <div class="stat-card border-l-4 border-violet-500">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rata-rata / Transaksi</p>
                        <p class="text-xl font-extrabold text-gray-900 mt-1 leading-none">
                            Rp {{ $sales->total() > 0 ? number_format($total / $sales->total(), 0, ',', '.') : '0' }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-violet-600 font-medium mt-2">Nilai rata-rata</p>
            </div>
        </div>

        {{-- ── Tabel Transaksi ──────────────────────────── --}}
        <div class="card">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th>Barang</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $s)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="font-medium text-gray-800">{{ $s->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $s->created_at->format('H:i') }}</div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700 shrink-0">
                                            {{ strtoupper(substr($s->cashier_name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm text-gray-700">{{ $s->cashier_name }}</span>
                                    </div>
                                </td>
                                <td class="max-w-[240px]">
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $s->items->map(fn ($i) => $i->product_name . ' ×' . $i->qty)->join(', ') }}
                                    </p>
                                </td>
                                <td class="text-right font-bold text-blue-700 whitespace-nowrap">
                                    Rp {{ number_format($s->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <span class="text-4xl">📊</span>
                                        <p class="font-medium">Tidak ada transaksi pada rentang ini</p>
                                        <p class="text-sm">Coba ubah filter tanggal</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-5">{{ $sales->links() }}</div>
        </div>

    </div>
</x-app-layout>
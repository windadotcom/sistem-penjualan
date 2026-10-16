@php
    $badgeMap = [
        'baru'       => 'bg-amber-100 text-amber-700',
        'diproses'   => 'bg-sky-100 text-sky-700',
        'dikirim'    => 'bg-violet-100 text-violet-700',
        'selesai'    => 'bg-emerald-100 text-emerald-700',
        'dibatalkan' => 'bg-red-100 text-red-700',
    ];
    $iconMap = [
        'baru'       => '🔔',
        'diproses'   => '⚙️',
        'dikirim'    => '🚚',
        'selesai'    => '✅',
        'dibatalkan' => '❌',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-heading">Pesanan Online</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola pesanan masuk dari toko online</p>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        @include('partials.flash')

        {{-- ── Filter Status Tabs ──────────────────────── --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('orders.index') }}"
               class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-sm font-semibold transition
                      {{ !request('status') ? 'bg-gray-800 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                Semua
            </a>
            @foreach (['baru', 'diproses', 'dikirim', 'selesai', 'dibatalkan'] as $s)
                <a href="{{ route('orders.index', ['status' => $s]) }}"
                   class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-sm font-semibold transition
                          {{ request('status') === $s
                              ? 'ring-2 ring-offset-1 ring-emerald-400 ' . $badgeMap[$s]
                              : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 ' . '' }}">
                    <span>{{ $iconMap[$s] }}</span>
                    {{ ucfirst($s) }}
                </a>
            @endforeach
        </div>

        {{-- ── Order Cards ─────────────────────────────── --}}
        <div class="space-y-3">
            @forelse ($orders as $o)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">

                    {{-- Header card --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3.5 border-b border-gray-50 bg-gray-50/60">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-base">
                                {{ $iconMap[$o->status] ?? '📦' }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 text-sm">Pesanan #{{ $o->id }}</div>
                                <div class="text-xs text-gray-400">{{ $o->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        <span class="badge text-xs {{ $badgeMap[$o->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $iconMap[$o->status] ?? '' }} {{ ucfirst($o->status) }}
                        </span>
                    </div>

                    {{-- Body card --}}
                    <div class="px-5 py-4 space-y-3">

                        {{-- Info pelanggan --}}
                        <div class="grid sm:grid-cols-3 gap-3 text-sm">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <div>
                                    <div class="text-xs text-gray-400 font-medium">Pelanggan</div>
                                    <div class="font-semibold text-gray-700">{{ $o->customer_name }}</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <div>
                                    <div class="text-xs text-gray-400 font-medium">Kontak</div>
                                    <div class="font-semibold text-gray-700">{{ $o->customer_phone }}</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <div>
                                    <div class="text-xs text-gray-400 font-medium">Pembayaran</div>
                                    <div class="font-semibold text-gray-700">{{ $o->payment_method }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="flex items-start gap-2 text-sm bg-gray-50 rounded-xl px-3 py-2">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-gray-600">{{ $o->customer_address }}</span>
                        </div>

                        {{-- Items --}}
                        <div class="text-xs text-gray-500 bg-emerald-50 rounded-xl px-3 py-2">
                            📦 {{ $o->items->map(fn ($i) => $i->product_name . ' ×' . $i->qty)->join(', ') }}
                        </div>

                        {{-- Footer: total + update status --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-gray-100">
                            <div class="text-lg font-extrabold text-emerald-700">
                                Rp {{ number_format($o->total, 0, ',', '.') }}
                            </div>

                            @if ($o->status !== 'dibatalkan')
                                <form method="POST" action="{{ route('orders.update', $o) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status"
                                            class="border border-gray-200 rounded-xl text-xs px-3 py-2 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 focus:outline-none bg-white">
                                        @foreach (['baru', 'diproses', 'dikirim', 'selesai', 'dibatalkan'] as $s)
                                            <option value="{{ $s }}" @selected($o->status === $s)>
                                                {{ $iconMap[$s] }} {{ ucfirst($s) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary py-2 px-3 text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Simpan
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">Pesanan dibatalkan</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card text-center py-12">
                    <div class="flex flex-col items-center gap-2 text-gray-400">
                        <span class="text-4xl">📭</span>
                        <p class="font-semibold text-gray-600">Belum ada pesanan</p>
                        <p class="text-sm">Pesanan online akan muncul di sini</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div>{{ $orders->links() }}</div>
    </div>
</x-app-layout>

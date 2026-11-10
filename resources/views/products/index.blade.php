<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-heading">Data Barang</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola produk dan stok toko</p>
        </div>
        <a href="{{ route('products.create') }}"
           class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Barang
        </a>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        @include('partials.flash')

        <div class="card">
            {{-- Search bar --}}
            <form method="GET" class="mb-5 flex gap-2">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Cari nama atau kategori..."
                           class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(request('q'))
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th class="text-right">Harga Jual</th>
                            <th class="text-right">Stok</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $p)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        {{-- Thumbnail --}}
                                        @if ($p->hasImage())
                                            <img src="{{ $p->image_url }}"
                                                 alt="{{ $p->name }}"
                                                 class="w-10 h-10 rounded-lg object-cover border border-gray-200 shrink-0">
                                        @else
                                            @php
                                                $tEmoji = match(strtolower($p->category ?? '')) {
                                                    'pakaian'   => ['e' => '👕', 'bg' => 'from-blue-50 to-indigo-100'],
                                                    'celana'    => ['e' => '👖', 'bg' => 'from-violet-50 to-purple-100'],
                                                    'aksesoris' => ['e' => '👜', 'bg' => 'from-pink-50 to-rose-100'],
                                                    'sembako'   => ['e' => '🛒', 'bg' => 'from-amber-50 to-yellow-100'],
                                                    'minuman'   => ['e' => '🥤', 'bg' => 'from-cyan-50 to-sky-100'],
                                                    default     => ['e' => '📦', 'bg' => 'from-blue-50 to-indigo-100'],
                                                };
                                            @endphp
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $tEmoji['bg'] }} flex items-center justify-center text-lg shrink-0 border border-gray-100">
                                                {{ $tEmoji['e'] }}
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-800">{{ $p->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-gray-100 text-gray-600">{{ $p->category ?: '—' }}</span>
                                </td>
                                <td class="text-right font-semibold text-gray-800">
                                    Rp {{ number_format($p->price, 0, ',', '.') }}
                                </td>
                                <td class="text-right">
                                    <span class="badge {{ $p->stock === 0 ? 'bg-red-100 text-red-700' : ($p->stock <= 5 ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                        {{ $p->stock === 0 ? 'Habis' : $p->stock . ' unit' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('products.edit', $p) }}"
                                           class="btn btn-secondary py-1.5 px-3 text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('products.destroy', $p) }}" method="POST"
                                              onsubmit="return confirm('Hapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger py-1.5 px-3 text-xs">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <span class="text-4xl">📭</span>
                                        <p class="font-medium">Belum ada barang</p>
                                        <a href="{{ route('products.create') }}" class="text-blue-600 text-sm font-semibold hover:underline">+ Tambah sekarang</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">{{ $products->links() }}</div>
        </div>
    </div>
</x-app-layout>

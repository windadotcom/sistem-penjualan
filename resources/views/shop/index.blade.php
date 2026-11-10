<x-shop-layout>

    {{-- ── Hero Banner ──────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-600 text-white px-6 py-8 sm:px-10">
        <div class="relative z-10">
            <p class="text-blue-100 text-sm font-semibold uppercase tracking-widest mb-1">Selamat datang</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold leading-tight">Belanja Mudah &amp; Cepat</h1>
            <p class="text-blue-100 text-sm mt-2 max-w-xs">Temukan produk terbaik dengan harga terjangkau, dikirim langsung ke rumah Anda.</p>
        </div>
        {{-- Decorative circles --}}
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full"></div>
        <div class="absolute -right-4 -bottom-12 w-32 h-32 bg-white/5 rounded-full"></div>
    </div>

    {{-- ── Filter & Search ──────────────────────────── --}}
    <form method="GET" class="flex flex-wrap gap-2 items-center">
        <div class="relative flex-1 min-w-[200px]">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari barang..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
        </div>
        <select name="kategori" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $c)
                <option value="{{ $c }}" @selected(request('kategori') === $c)>{{ $c }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary py-2.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Cari
        </button>
    </form>

    {{-- ── Product Grid ──────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse ($products as $p)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                {{-- Product image --}}
                <div class="h-44 relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-50">
                    @if ($p->hasImage())
                        <img src="{{ $p->image_url }}"
                             alt="{{ $p->name }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        @php
                            $categoryEmoji = match(strtolower($p->category ?? '')) {
                                'pakaian'    => ['emoji' => '👕', 'bg' => 'from-blue-50 to-indigo-100'],
                                'celana'     => ['emoji' => '👖', 'bg' => 'from-violet-50 to-purple-100'],
                                'aksesoris'  => ['emoji' => '👜', 'bg' => 'from-pink-50 to-rose-100'],
                                'sembako'    => ['emoji' => '🛒', 'bg' => 'from-amber-50 to-yellow-100'],
                                'minuman'    => ['emoji' => '🥤', 'bg' => 'from-cyan-50 to-sky-100'],
                                default      => ['emoji' => '📦', 'bg' => 'from-blue-50 to-indigo-50'],
                            };
                        @endphp
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2 bg-gradient-to-br {{ $categoryEmoji['bg'] }}">
                            <span class="text-5xl">{{ $categoryEmoji['emoji'] }}</span>
                            <span class="text-xs text-gray-400 font-medium">Belum ada foto</span>
                        </div>
                    @endif
                    {{-- Badge stok --}}
                    @if ($p->stock <= 0)
                        <span class="absolute top-2 right-2 badge bg-red-100 text-red-600">Habis</span>
                    @elseif ($p->stock <= 5)
                        <span class="absolute top-2 right-2 badge bg-amber-100 text-amber-700">Hampir habis</span>
                    @endif
                </div>

                <div class="p-4 flex flex-col flex-1">
                    {{-- Kategori --}}
                    <span class="badge bg-blue-50 text-blue-700 self-start mb-2">{{ $p->category ?: 'Umum' }}</span>

                    {{-- Nama --}}
                    <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 flex-1">{{ $p->name }}</h3>

                    {{-- Harga --}}
                    <div class="text-blue-700 font-extrabold text-lg mt-2">
                        Rp {{ number_format($p->price, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-gray-400 mb-3">Stok: {{ $p->stock }} unit</div>

                    {{-- Add to cart --}}
                    @if ($p->stock > 0)
                        <form method="POST" action="{{ route('cart.add', $p) }}" class="flex gap-2 mt-auto">
                            @csrf
                            <input type="number" name="qty" value="1" min="1" max="{{ $p->stock }}"
                                   class="w-14 border border-gray-200 rounded-xl text-sm text-center px-2 py-2 focus:border-blue-400 focus:outline-none">
                            <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold py-2 transition flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Keranjang
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full mt-auto bg-gray-100 text-gray-400 rounded-xl text-xs font-bold py-2 cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <div class="flex flex-col items-center gap-2 text-gray-400">
                    <span class="text-5xl">🔍</span>
                    <p class="font-semibold text-gray-600">Produk tidak ditemukan</p>
                    <a href="{{ route('shop.index') }}" class="text-blue-600 text-sm font-semibold hover:underline">Lihat semua produk →</a>
                </div>
            </div>
        @endforelse
    </div>

    <div>{{ $products->links() }}</div>

</x-shop-layout>

<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-heading">Kasir</h1>
            <p class="text-xs text-gray-500 mt-0.5">Proses transaksi penjualan</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5">
            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span id="live-clock"></span>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        @include('partials.flash')

        <div x-data="kasir(@js($products->map(fn($p) => [
            'id'        => $p->id,
            'name'      => $p->name,
            'price'     => $p->price,
            'stock'     => $p->stock,
            'image_url' => $p->image_url,
            'has_image' => $p->hasImage(),
        ])))" class="grid lg:grid-cols-5 gap-4">

            {{-- ── Kiri: Pilih Barang ──────────────────────── --}}
            <div class="lg:col-span-3 space-y-4">

                {{-- Panel pilih barang --}}
                <div class="card">
                    <h2 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        Tambah Item
                    </h2>

                    {{-- Preview barang terpilih --}}
                    <template x-if="selectedProduct">
                        <div class="flex items-center gap-3 mb-4 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <template x-if="selectedProduct.has_image">
                                <img :src="selectedProduct.image_url" :alt="selectedProduct.name"
                                     class="w-14 h-14 rounded-lg object-cover border border-gray-200 shrink-0">
                            </template>
                            <template x-if="!selectedProduct.has_image">
                                <div class="w-14 h-14 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center text-2xl shrink-0 border border-gray-100">
                                    📦
                                </div>
                            </template>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm" x-text="selectedProduct.name"></p>
                                <p class="text-xs text-blue-700 font-bold" x-text="rp(selectedProduct.price)"></p>
                                <p class="text-xs text-gray-400" x-text="'Stok: ' + selectedProduct.stock + ' unit'"></p>
                            </div>
                        </div>
                    </template>

                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih Barang</label>
                            <select x-model="selected" @change="updateSelected()"
                                    class="w-full border border-gray-200 rounded-xl text-sm px-3 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition bg-white">
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->name }} — Rp {{ number_format($p->price, 0, ',', '.') }}
                                        (stok {{ $p->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-24">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jumlah</label>
                            <input type="number" min="1" x-model.number="qty"
                                   class="w-full border border-gray-200 rounded-xl text-sm px-3 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition text-center">
                        </div>
                        <button type="button" @click="add()"
                                class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Tambah
                        </button>
                    </div>

                    <p x-show="msg" x-text="msg"
                       class="mt-3 text-xs text-red-600 bg-red-50 border border-red-200 rounded-lg px-3 py-2 flex items-center gap-1.5">
                    </p>
                </div>

                {{-- Keranjang --}}
                <div class="card">
                    <h2 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-sky-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        Keranjang
                        <span x-show="cart.length > 0"
                              x-text="cart.length + ' item'"
                              class="badge bg-sky-100 text-sky-700 ms-auto"></span>
                    </h2>

                    <p x-show="cart.length === 0" class="text-sm text-gray-400 text-center py-6">
                        🛒 Keranjang masih kosong
                    </p>

                    <div x-show="cart.length > 0">
                        <div class="overflow-x-auto">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th class="text-right">Harga</th>
                                        <th class="text-right">Jml</th>
                                        <th class="text-right">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(c, i) in cart" :key="c.id">
                                        <tr>
                                            <td class="font-medium text-gray-800">
                                                <div class="flex items-center gap-2">
                                                    <template x-if="c.has_image">
                                                        <img :src="c.image_url" :alt="c.name"
                                                             class="w-9 h-9 rounded-lg object-cover border border-gray-200 shrink-0">
                                                    </template>
                                                    <template x-if="!c.has_image">
                                                        <div class="w-9 h-9 rounded-lg bg-blue-50 border border-gray-100 flex items-center justify-center text-base shrink-0">📦</div>
                                                    </template>
                                                    <span x-text="c.name"></span>
                                                </div>
                                            </td>
                                            <td class="text-right text-gray-600" x-text="rp(c.price)"></td>
                                            <td class="text-right">
                                                <span class="badge bg-gray-100 text-gray-700" x-text="c.qty + 'x'"></span>
                                            </td>
                                            <td class="text-right font-semibold text-gray-800" x-text="rp(c.price * c.qty)"></td>
                                            <td class="text-right">
                                                <button type="button" @click="remove(i)"
                                                        class="w-6 h-6 rounded-full bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center ms-auto transition">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Kanan: Pembayaran ───────────────────────── --}}
            <div class="lg:col-span-2">
                <div class="card sticky top-4">
                    <h2 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        Pembayaran
                    </h2>

                    {{-- Total --}}
                    <div class="bg-blue-50 rounded-xl px-4 py-3 mb-4 flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-600">Total</span>
                        <span class="text-xl font-extrabold text-blue-700" x-text="rp(total)"></span>
                    </div>

                    <form method="POST" action="{{ route('kasir.store') }}" class="space-y-4">
                        @csrf

                        <template x-for="(c, i) in cart" :key="c.id">
                            <span>
                                <input type="hidden" :name="'items[' + i + '][product_id]'" :value="c.id">
                                <input type="hidden" :name="'items[' + i + '][qty]'" :value="c.qty">
                            </span>
                        </template>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Uang Dibayar</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                                <input name="paid" type="number" min="0" x-model.number="paid" required
                                       placeholder="0"
                                       class="w-full pl-10 pr-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kembalian</label>
                            <div class="px-3.5 py-2.5 rounded-xl text-sm font-bold"
                                 :class="paid >= total && total > 0 ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-gray-50 text-gray-400 border border-gray-200'"
                                 x-text="rp(Math.max(paid - total, 0))">
                            </div>
                        </div>

                        <button type="submit"
                                :disabled="cart.length === 0 || paid < total"
                                class="w-full btn btn-primary justify-center py-3 text-sm disabled:opacity-40 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Selesaikan Transaksi
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Live clock
        function updateClock() {
            const el = document.getElementById('live-clock');
            if (el) el.textContent = new Date().toLocaleTimeString('id-ID');
        }
        updateClock();
        setInterval(updateClock, 1000);

        function kasir(products) {
            return {
                products: products,
                cart: [],
                selected: products.length ? String(products[0].id) : '',
                selectedProduct: products.length ? products[0] : null,
                qty: 1,
                paid: '',
                msg: '',

                get total() {
                    return this.cart.reduce((a, c) => a + c.price * c.qty, 0);
                },

                rp(n) {
                    return 'Rp ' + Number(n || 0).toLocaleString('id-ID');
                },

                updateSelected() {
                    this.selectedProduct = this.products.find(x => String(x.id) === String(this.selected)) || null;
                },

                add() {
                    const p = this.products.find(x => String(x.id) === String(this.selected));
                    const q = parseInt(this.qty);
                    if (!p || !q || q < 1) { this.msg = 'Pilih barang dan jumlah yang benar.'; return; }
                    const ex = this.cart.find(c => c.id === p.id);
                    const newQty = (ex ? ex.qty : 0) + q;
                    if (newQty > p.stock) { this.msg = 'Stok tidak cukup (tersedia ' + p.stock + ').'; return; }
                    if (ex) { ex.qty = newQty; } else { this.cart.push({ id: p.id, name: p.name, price: p.price, qty: q, image_url: p.image_url, has_image: p.has_image }); }
                    this.qty = 1;
                    this.msg = '';
                },

                remove(i) { this.cart.splice(i, 1); }
            };
        }
    </script>
</x-app-layout>

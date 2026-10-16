<x-shop-layout>
    <h1 class="text-2xl font-extrabold text-gray-800">Keranjang Belanja</h1>

    @if (count($items) === 0)
        <div class="bg-white rounded-2xl p-12 text-center">
            <div class="text-5xl mb-3">🛒</div>
            <p class="font-semibold text-gray-600 mb-1">Keranjang masih kosong</p>
            <a href="{{ route('shop.index') }}" class="text-emerald-600 text-sm font-semibold hover:underline">Mulai belanja →</a>
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">Barang</th>
                            <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">Harga</th>
                            <th class="text-center py-3 px-4 text-xs font-bold text-gray-500 uppercase">Jumlah</th>
                            <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">Subtotal</th>
                            <th class="py-3 px-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $i)
                            <tr class="border-b border-gray-50">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        @if ($i['product']->hasImage())
                                            <img src="{{ $i['product']->image_url }}"
                                                 alt="{{ $i['product']->name }}"
                                                 class="w-12 h-12 rounded-lg object-cover border border-gray-200 shrink-0">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-emerald-50 to-teal-100 flex items-center justify-center text-xl shrink-0 border border-gray-100">
                                                📦
                                            </div>
                                        @endif
                                        <span class="font-semibold text-gray-800">{{ $i['product']->name }}</span>
                                    </div>
                                </td>
                                <td class="text-right px-4 text-gray-500">Rp {{ number_format($i['product']->price, 0, ',', '.') }}</td>
                                <td class="text-center px-4">
                                    <form method="POST" action="{{ route('cart.update', $i['product']) }}" class="inline-flex gap-1 items-center">
                                        @csrf @method('PATCH')
                                        <input type="number" name="qty" value="{{ $i['qty'] }}" min="0" max="{{ $i['product']->stock }}"
                                               class="w-14 border border-gray-200 rounded-lg text-sm text-center py-1.5 focus:border-emerald-400 focus:outline-none">
                                        <button class="text-xs bg-teal-50 text-teal-700 border border-teal-200 rounded-lg px-2 py-1.5 hover:bg-teal-100 transition">Ubah</button>
                                    </form>
                                </td>
                                <td class="text-right px-4 font-bold text-emerald-700">Rp {{ number_format($i['subtotal'], 0, ',', '.') }}</td>
                                <td class="px-4">
                                    <form method="POST" action="{{ route('cart.remove', $i['product']) }}">
                                        @csrf @method('DELETE')
                                        <button class="w-7 h-7 rounded-full bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5 h-fit space-y-4">
                <h2 class="font-bold text-gray-800">Ringkasan</h2>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="border-t pt-3 flex justify-between font-extrabold text-lg text-emerald-700">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout.show') }}"
                   class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white font-bold rounded-xl py-3 text-sm transition shadow-sm">
                    Lanjut Checkout
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('shop.index') }}" class="block text-center text-xs text-gray-400 hover:text-emerald-600 transition">← Lanjut belanja</a>
            </div>
        </div>
    @endif
</x-shop-layout>

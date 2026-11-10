<x-shop-layout>
    <div class="max-w-lg mx-auto bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-600 px-6 py-8 text-white text-center">
            <div class="text-5xl mb-2">🎉</div>
            <h1 class="text-2xl font-extrabold">Pesanan Berhasil!</h1>
            <p class="text-blue-100 text-sm mt-1">No. Pesanan <b>#{{ $sale->id }}</b> — simpan nomor ini</p>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-sm">
                @foreach ($sale->items as $i)
                    <div class="flex justify-between text-gray-700">
                        <span>{{ $i->product_name }} × {{ $i->qty }}</span>
                        <span>Rp {{ number_format($i->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between font-extrabold text-blue-700 border-t pt-2 mt-2 text-base">
                    <span>Total</span>
                    <span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if ($sale->payment_method === 'Transfer')
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 text-sm">
                    💳 Transfer <b>Rp {{ number_format($sale->total, 0, ',', '.') }}</b> ke <b>BCA 1234567890 a.n. Toko Anda</b>, lalu konfirmasi ke admin.
                </div>
            @else
                <div class="bg-sky-50 border border-sky-200 text-sky-800 rounded-xl p-4 text-sm">
                    🚪 Bayar tunai saat barang tiba (COD).
                </div>
            @endif

            <a href="{{ route('shop.index') }}"
               class="w-full flex items-center justify-center gap-2 btn btn-primary justify-center py-3">
                Kembali Belanja
            </a>
        </div>
    </div>
</x-shop-layout>

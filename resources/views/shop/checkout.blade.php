<x-shop-layout>
    <h1 class="text-2xl font-extrabold text-gray-800">Checkout</h1>

    <div class="grid md:grid-cols-5 gap-5">
        <form method="POST" action="{{ route('checkout.store') }}" class="md:col-span-3 bg-white rounded-2xl shadow-sm p-6 space-y-4">
            @csrf

            <div>
                <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required
                       class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                @error('customer_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="customer_phone" class="block text-sm font-semibold text-gray-700 mb-1.5">No. HP / WhatsApp <span class="text-red-500">*</span></label>
                <input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required
                       class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">
                @error('customer_phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="customer_address" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Pengiriman <span class="text-red-500">*</span></label>
                <textarea id="customer_address" name="customer_address" rows="3" required
                          class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none transition">{{ old('customer_address') }}</textarea>
                @error('customer_address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <div class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran</div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach (['COD' => ['🚪', 'Bayar di Tempat', 'COD'], 'Transfer' => ['🏦', 'Transfer Bank', 'Transfer']] as $val => [$icon, $label, $sub])
                        <label class="flex items-center gap-3 border-2 rounded-xl px-4 py-3 cursor-pointer transition
                                      {{ old('payment_method', 'COD') === $val ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                            <input type="radio" name="payment_method" value="{{ $val }}" class="text-blue-600 focus:ring-blue-400"
                                   @checked(old('payment_method', 'COD') === $val)>
                            <div>
                                <div class="font-semibold text-sm text-gray-800">{{ $icon }} {{ $label }}</div>
                                <div class="text-xs text-gray-400">{{ $sub }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <button class="w-full btn btn-primary justify-center py-3 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Buat Pesanan
            </button>
        </form>

        <div class="md:col-span-2 bg-white rounded-2xl shadow-sm p-5 h-fit space-y-3">
            <h2 class="font-bold text-gray-800">Ringkasan Pesanan</h2>
            @foreach ($items as $i)
                <div class="flex items-center gap-3 py-1">
                    @if ($i['product']->hasImage())
                        <img src="{{ $i['product']->image_url }}"
                             alt="{{ $i['product']->name }}"
                             class="w-10 h-10 rounded-lg object-cover border border-gray-100 shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-lg shrink-0 border border-gray-100">📦</div>
                    @endif
                    <div class="flex-1 flex justify-between text-sm text-gray-600">
                        <span>{{ $i['product']->name }} × {{ $i['qty'] }}</span>
                        <span class="font-semibold">Rp {{ number_format($i['subtotal'], 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
            <div class="border-t pt-3 flex justify-between font-extrabold text-lg text-blue-700">
                <span>Total</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</x-shop-layout>

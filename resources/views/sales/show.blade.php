<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('sales.index') }}" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition print:hidden">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="page-heading">Struk Transaksi</h1>
                <p class="text-xs text-gray-500 mt-0.5">Transaksi #{{ $sale->id }}</p>
            </div>
        </div>
        <div class="flex gap-2 print:hidden">
            <button onclick="window.print()"
                    class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak
            </button>
            <a href="{{ route('kasir.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Transaksi Baru
            </a>
        </div>
    </x-slot>

    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        @include('partials.flash')

        {{-- Struk Card --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

            {{-- Header struk --}}
            <div class="bg-gradient-to-r from-blue-700 to-indigo-600 px-6 py-5 text-white text-center">
                <div class="text-lg font-extrabold tracking-wide">{{ config('app.name', 'TOKO') }}</div>
                <div class="text-xs text-blue-100 mt-1">Struk Penjualan</div>
            </div>

            <div class="px-6 py-5 space-y-4">

                {{-- Info transaksi --}}
                <div class="flex justify-between text-xs text-gray-500 pb-3 border-b border-dashed border-gray-200">
                    <span>No. Transaksi: <b class="text-gray-700">#{{ $sale->id }}</b></span>
                    <span>{{ $sale->created_at->format('d/m/Y H:i') }}</span>
                </div>
                
                <div class="text-xs text-gray-500 pb-3 border-b border-dashed border-gray-200 space-y-0.5">
                    @if ($sale->source === 'kasir')
                        <div>Kasir: <b class="text-gray-700">{{ $sale->user?->name ?? '-' }}</b></div>
                    @else
                        <div>Pelanggan: <b class="text-gray-700">{{ $sale->customer_name ?? '-' }}</b></div>
                    @if ($sale->customer_phone)
                        <div>Telp: <b class="text-gray-700">{{ $sale->customer_phone }}</b></div>
                    @endif
                    @if ($sale->customer_address)
                        <div>Alamat: <b class="text-gray-700">{{ $sale->customer_address }}</b></div>
                    @endif
                    @if ($sale->payment_method)
                        <div>Pembayaran: <b class="text-gray-700">{{ ucfirst($sale->payment_method) }}</b></div>
                    @endif
                @endif
            </div>

                {{-- Item list --}}
                <div class="space-y-2">
                    @foreach ($sale->items as $i)
                        <div class="flex justify-between text-sm">
                            <div>
                                <div class="font-medium text-gray-800">{{ $i->product_name }}</div>
                                <div class="text-xs text-gray-400">{{ $i->qty }} × Rp {{ number_format($i->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="font-semibold text-gray-700 whitespace-nowrap">
                                Rp {{ number_format($i->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Total area --}}
                <div class="border-t-2 border-dashed border-gray-300 pt-3 space-y-1.5">
                    <div class="flex justify-between text-xl font-extrabold text-blue-700">
                        <span>Total</span>
                        <span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                    </div>

                    @if ($sale->source === 'kasir')
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Dibayar</span>
                            <span>Rp {{ number_format($sale->paid, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold text-blue-600">
                            <span>Kembalian</span>
                            <span>Rp {{ number_format($sale->paid - $sale->total, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Sumber</span>
                            <span class="badge bg-sky-100 text-sky-700">Pesanan Online</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Status</span>
                            <span class="font-semibold text-gray-800">{{ ucfirst($sale->status) }}</span>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="text-center text-xs text-gray-400 pt-2 border-t border-dashed border-gray-200">
                    Terima kasih telah berbelanja 🙏
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

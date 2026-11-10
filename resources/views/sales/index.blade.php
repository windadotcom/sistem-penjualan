<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-heading">Riwayat Transaksi</h1>
            <p class="text-xs text-gray-500 mt-0.5">Semua penjualan yang tercatat</p>
        </div>
        <a href="{{ route('kasir.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Transaksi Baru
        </a>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th>Barang</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $s)
                            <tr>
                                <td>
                                    <span class="badge bg-gray-100 text-gray-600">#{{ $s->id }}</span>
                                </td>
                                <td class="whitespace-nowrap text-gray-600">
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
                                <td class="max-w-[220px]">
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $s->items->map(fn ($i) => $i->product_name . ' ×' . $i->qty)->join(', ') }}
                                    </p>
                                </td>
                                <td class="text-right font-bold text-blue-700 whitespace-nowrap">
                                    Rp {{ number_format($s->total, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('sales.show', $s) }}"
                                       class="btn btn-secondary py-1.5 px-3 text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Struk
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <span class="text-4xl">🧾</span>
                                        <p class="font-medium">Belum ada transaksi</p>
                                        <a href="{{ route('kasir.create') }}" class="text-blue-600 text-sm font-semibold hover:underline">Mulai transaksi pertama →</a>
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

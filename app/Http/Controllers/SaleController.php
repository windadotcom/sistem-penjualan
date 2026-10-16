<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    // Riwayat transaksi (kasir hanya melihat miliknya, admin melihat semua)
    public function index()
    {
        $sales = Sale::with(['user', 'items'])
            ->when(! auth()->user()->isAdmin(), fn ($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    // Halaman kasir
    public function create()
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'stock']);

        return view('sales.create', compact('products'));
    }

    // Simpan transaksi
    public function store(Request $request)
    {
        $data = $request->validate([
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.qty'        => ['required', 'integer', 'min:1'],
            'paid'               => ['required', 'integer', 'min:0'],
        ]);

        try {
            $sale = DB::transaction(function () use ($data) {
                // Gabungkan jika ada barang yang sama muncul dua kali
                $qtyById = collect($data['items'])
                    ->groupBy('product_id')
                    ->map(fn ($group) => $group->sum('qty'));

                $total = 0;
                $rows  = [];

                foreach ($qtyById as $productId => $qty) {
                    // lockForUpdate: kunci baris agar dua kasir tidak menjual stok yang sama
                    $product = Product::lockForUpdate()->findOrFail($productId);

                    if ($product->stock < $qty) {
                        throw new \RuntimeException("Stok {$product->name} tidak cukup (tersisa {$product->stock}).");
                    }

                    // Harga diambil dari database, BUKAN dari browser
                    $subtotal = $product->price * $qty;
                    $total   += $subtotal;

                    $rows[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'price'        => $product->price,
                        'qty'          => $qty,
                        'subtotal'     => $subtotal,
                    ];

                    $product->decrement('stock', $qty);
                }

                if ($data['paid'] < $total) {
                    throw new \RuntimeException('Uang dibayar kurang dari total belanja.');
                }

                $sale = Sale::create([
                    'user_id' => auth()->id(),
                    'total'   => $total,
                    'paid'    => $data['paid'],
                ]);

                $sale->items()->createMany($rows);

                return $sale;
            });
        } catch (\RuntimeException $e) {
            // Transaksi dibatalkan otomatis, stok tidak berubah
            return back()->withErrors(['kasir' => $e->getMessage()]);
        }

        return redirect()->route('sales.show', $sale)->with('status', 'Transaksi berhasil disimpan.');
    }

    // Struk
    public function show(Sale $sale)
    {
        if (! auth()->user()->isAdmin() && $sale->user_id !== auth()->id()) {
            abort(403);
        }

        $sale->load('items', 'user');

        return view('sales.show', compact('sale'));
    }
}
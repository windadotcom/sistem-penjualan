<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Sale::with('items')
            ->where('source', 'online')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function update(Request $request, Sale $sale)
    {
        abort_unless($sale->source === 'online', 404);

        $new = $request->validate([
            'status' => ['required', 'in:baru,diproses,dikirim,selesai,dibatalkan'],
        ])['status'];

        try {
            DB::transaction(function () use ($sale, $new) {
                $sale = Sale::with('items')->lockForUpdate()->findOrFail($sale->id);

                if ($sale->status === 'dibatalkan') {
                    throw new \RuntimeException('Pesanan yang sudah dibatalkan tidak bisa diubah lagi.');
                }

                // Batal = stok dikembalikan
                if ($new === 'dibatalkan') {
                    foreach ($sale->items as $item) {
                        if ($item->product_id) {
                            Product::where('id', $item->product_id)->increment('stock', $item->qty);
                        }
                    }
                }

                $sale->update(['status' => $new]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('status', 'Status pesanan diperbarui.');
    }
}
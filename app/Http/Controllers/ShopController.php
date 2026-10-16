<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    // Katalog barang
    public function index(Request $request)
    {
        $products = Product::where('stock', '>', 0)
            ->when($request->q, fn ($q, $s) => $q->where(
                fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('category', 'like', "%{$s}%")
            ))
            ->when($request->kategori, fn ($q, $c) => $q->where('category', $c))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Product::where('stock', '>', 0)->distinct()->orderBy('category')->pluck('category');

        return view('shop.index', compact('products', 'categories'));
    }

    // Isi keranjang disimpan di session: [id_barang => jumlah]
    private function cartItems(): array
    {
        $cart     = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $id => $qty) {
            $p = $products->get($id);
            if (! $p || $p->stock < 1) {
                continue;
            }

            $qty      = min($qty, $p->stock);
            $subtotal = $p->price * $qty;
            $total   += $subtotal;
            $items[]  = ['product' => $p, 'qty' => $qty, 'subtotal' => $subtotal];
        }

        return [$items, $total];
    }

    public function cart()
    {
        [$items, $total] = $this->cartItems();

        return view('shop.cart', compact('items', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $qty  = max(1, (int) $request->input('qty', 1));
        $cart = session('cart', []);
        $new  = ($cart[$product->id] ?? 0) + $qty;

        if ($new > $product->stock) {
            return back()->with('error', "Stok {$product->name} hanya tersisa {$product->stock}.");
        }

        $cart[$product->id] = $new;
        session(['cart' => $cart]);

        return back()->with('status', "{$product->name} ditambahkan ke keranjang.");
    }

    public function update(Request $request, Product $product)
    {
        $qty  = (int) $request->input('qty', 1);
        $cart = session('cart', []);

        if ($qty < 1) {
            unset($cart[$product->id]);
        } elseif ($qty > $product->stock) {
            return back()->with('error', "Stok {$product->name} hanya tersisa {$product->stock}.");
        } else {
            $cart[$product->id] = $qty;
        }

        session(['cart' => $cart]);

        return back();
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('status', 'Barang dihapus dari keranjang.');
    }

    public function checkout()
    {
        [$items, $total] = $this->cartItems();

        if (! $items) {
            return redirect()->route('shop.index')->with('error', 'Keranjang masih kosong.');
        }

        return view('shop.checkout', compact('items', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name'    => ['required', 'string', 'max:100'],
            'customer_phone'   => ['required', 'string', 'max:30'],
            'customer_address' => ['required', 'string', 'max:500'],
            'payment_method'   => ['required', 'in:COD,Transfer'],
        ]);

        $cart = session('cart', []);

        if (! $cart) {
            return redirect()->route('shop.index')->with('error', 'Keranjang masih kosong.');
        }

        try {
            $sale = DB::transaction(function () use ($cart, $data) {
                $total = 0;
                $rows  = [];

                foreach ($cart as $productId => $qty) {
                    // Harga & stok selalu diambil dari database, bukan dari browser
                    $product = Product::lockForUpdate()->find($productId);

                    if (! $product) {
                        throw new \RuntimeException('Ada barang di keranjang yang sudah tidak tersedia.');
                    }

                    if ($product->stock < $qty) {
                        throw new \RuntimeException("Stok {$product->name} tidak cukup (tersisa {$product->stock}).");
                    }

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

                $sale = Sale::create($data + [
                    'user_id' => null,
                    'total'   => $total,
                    'paid'    => 0,
                    'source'  => 'online',
                    'status'  => 'baru',
                ]);

                $sale->items()->createMany($rows);

                return $sale;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        session()->forget('cart');
        session(['last_order' => $sale->id]);   // hanya pembeli ini yang boleh melihat halaman sukses

        return redirect()->route('checkout.done', $sale);
    }

    public function done(Sale $sale)
    {
        abort_unless(session('last_order') === $sale->id, 404);

        $sale->load('items');

        return view('shop.done', compact('sale'));
    }
}
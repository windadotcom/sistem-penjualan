<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $revenue      = Sale::valid()->sum('total');
        $transactions = Sale::valid()->count();
        $sold         = SaleItem::whereHas('sale', fn ($q) => $q->valid())->sum('qty');
        $productCount = Product::count();
        $newOrders    = Sale::where('source', 'online')->where('status', 'baru')->count();

        $top = SaleItem::whereHas('sale', fn ($q) => $q->valid())
            ->select('product_name', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $low = Product::where('stock', '<=', 5)->orderBy('stock')->get();

        return view('dashboard', compact('revenue', 'transactions', 'sold', 'productCount', 'newOrders', 'top', 'low'));
    }
}
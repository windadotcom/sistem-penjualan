<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/* ===== TOKO ONLINE (publik, tanpa login) ===== */
Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/keranjang', [ShopController::class, 'cart'])->name('cart.index');
Route::post('/keranjang/{product}', [ShopController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{product}', [ShopController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{product}', [ShopController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout.show');
Route::post('/checkout', [ShopController::class, 'placeOrder'])->name('checkout.store');
Route::get('/pesanan-berhasil/{sale}', [ShopController::class, 'done'])->name('checkout.done');

/* ===== AREA STAF (harus login) ===== */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kasir dan admin
    Route::get('/kasir', [SaleController::class, 'create'])->name('kasir.create');
    Route::post('/kasir', [SaleController::class, 'store'])->name('kasir.store');
    Route::get('/transaksi', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/transaksi/{sale}', [SaleController::class, 'show'])->name('sales.show');

    // Hanya admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('products', ProductController::class)->except('show');
        Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
        Route::patch('/pesanan/{sale}', [OrderController::class, 'update'])->name('orders.update');
        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/laporan/csv', [ReportController::class, 'csv'])->name('reports.csv');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
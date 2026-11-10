<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class FashionProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ── Pakaian ────────────────────────────────────
            ['name' => 'Kaos Polos Pria',        'category' => 'Pakaian', 'price' => 45000,  'stock' => 50],
            ['name' => 'Kemeja Flannel',          'category' => 'Pakaian', 'price' => 120000, 'stock' => 25],
            ['name' => 'Jaket Hoodie Unisex',     'category' => 'Pakaian', 'price' => 185000, 'stock' => 20],
            ['name' => 'Dress Casual Wanita',     'category' => 'Pakaian', 'price' => 155000, 'stock' => 15],
            ['name' => 'Polo Shirt Pria',         'category' => 'Pakaian', 'price' => 95000,  'stock' => 30],

            // ── Celana ─────────────────────────────────────
            ['name' => 'Celana Jeans Slim Fit',  'category' => 'Celana',  'price' => 195000, 'stock' => 20],
            ['name' => 'Celana Chino Panjang',   'category' => 'Celana',  'price' => 165000, 'stock' => 18],
            ['name' => 'Celana Pendek Cargo',    'category' => 'Celana',  'price' => 85000,  'stock' => 35],
            ['name' => 'Celana Training Sport',  'category' => 'Celana',  'price' => 75000,  'stock' => 40],
            ['name' => 'Rok Midi Wanita',        'category' => 'Celana',  'price' => 110000, 'stock' => 12],

            // ── Aksesoris ──────────────────────────────────
            ['name' => 'Topi Baseball Cap',      'category' => 'Aksesoris', 'price' => 65000,  'stock' => 45],
            ['name' => 'Tas Selempang Mini',     'category' => 'Aksesoris', 'price' => 135000, 'stock' => 22],
            ['name' => 'Ikat Pinggang Kulit',    'category' => 'Aksesoris', 'price' => 55000,  'stock' => 30],
            ['name' => 'Kacamata Sunglasses',    'category' => 'Aksesoris', 'price' => 89000,  'stock' => 28],
            ['name' => 'Kaos Kaki 3 Pasang',     'category' => 'Aksesoris', 'price' => 35000,  'stock' => 60],
            ['name' => 'Dompet Pria Slim',       'category' => 'Aksesoris', 'price' => 75000,  'stock' => 20],
        ];

        foreach ($products as $data) {
            // Hanya insert jika belum ada (berdasarkan nama)
            Product::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}

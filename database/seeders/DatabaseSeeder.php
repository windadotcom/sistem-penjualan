<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Satu',
            'email' => 'kasir@toko.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $products = [
            ['Beras 5kg', 'Sembako', 68000, 20],
            ['Minyak Goreng 1L', 'Sembako', 17000, 30],
            ['Gula Pasir 1kg', 'Sembako', 16000, 4],
            ['Kopi Sachet', 'Minuman', 2500, 100],
        ];

        foreach ($products as [$name, $category, $price, $stock]) {
            Product::create(compact('name', 'category', 'price', 'stock'));
        }
    }
}
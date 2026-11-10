<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    /**
     * Mapping nama produk → URL gambar dari Unsplash (free, no API key).
     * Ukuran 400x400 px sudah cukup untuk thumbnail produk.
     */
    private array $imageMap = [
        // Sembako
        'Beras 5kg'         => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=400&fit=crop',
        'Minyak Goreng 1L'  => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=400&h=400&fit=crop',
        'Gula Pasir 1kg'    => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=400&fit=crop',

        // Minuman
        'Kopi Sachet'       => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=400&fit=crop',

        // Pakaian
        'Kaos Polos Pria'       => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop',
        'Kemeja Flannel'        => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=400&h=400&fit=crop',
        'Jaket Hoodie Unisex'   => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=400&h=400&fit=crop',
        'Dress Casual Wanita'   => 'https://images.unsplash.com/photo-1612336307429-8a898d10e223?w=400&h=400&fit=crop',
        'Polo Shirt Pria'       => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=400&h=400&fit=crop',

        // Celana
        'Celana Jeans Slim Fit' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=400&fit=crop',
        'Celana Chino Panjang'  => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=400&h=400&fit=crop',
        'Celana Pendek Cargo'   => 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=400&h=400&fit=crop',
        'Celana Training Sport' => 'https://images.unsplash.com/photo-1519058082700-08a0b56da9b4?w=400&h=400&fit=crop',
        'Rok Midi Wanita'       => 'https://images.unsplash.com/photo-1572804013427-4d7ca7268217?w=400&h=400&fit=crop',

        // Aksesoris
        'Topi Baseball Cap'     => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=400&h=400&fit=crop',
        'Tas Selempang Mini'    => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400&h=400&fit=crop',
        'Ikat Pinggang Kulit'   => 'https://images.unsplash.com/photo-1582552938357-32b906df40cb?w=400&h=400&fit=crop',
        'Kacamata Sunglasses'   => 'https://images.unsplash.com/photo-1508296695146-257a814070b4?w=400&h=400&fit=crop',
        'Kaos Kaki 3 Pasang'    => 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?w=400&h=400&fit=crop',
        'Dompet Pria Slim'      => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=400&h=400&fit=crop',
    ];

    public function run(): void
    {
        // Pastikan direktori products ada di storage
        Storage::disk('public')->makeDirectory('products');

        $products = Product::all();
        $berhasil = 0;
        $gagal    = 0;

        foreach ($products as $product) {
            $url = $this->imageMap[$product->name] ?? null;

            if (! $url) {
                $this->command->warn("  ⚠  Tidak ada URL untuk: {$product->name}");
                continue;
            }

            // Skip jika sudah punya gambar dan file ada di storage
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                $this->command->line("  ✓  Skip (sudah ada): {$product->name}");
                $berhasil++;
                continue;
            }

            $this->command->info("  ↓  Download: {$product->name}");

            try {
                $response = Http::withoutVerifying()->timeout(30)->get($url);

                if (! $response->successful()) {
                    $this->command->error("     Gagal (HTTP {$response->status()}): {$product->name}");
                    $gagal++;
                    continue;
                }

                // Deteksi ekstensi dari Content-Type
                $contentType = $response->header('Content-Type');
                $ext = match (true) {
                    str_contains($contentType, 'jpeg') => 'jpg',
                    str_contains($contentType, 'png')  => 'png',
                    str_contains($contentType, 'webp') => 'webp',
                    default                             => 'jpg',
                };

                // Hapus gambar lama jika ada
                if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                    Storage::disk('public')->delete($product->image_path);
                }

                // Simpan file ke storage/app/public/products/
                $filename    = 'products/' . Str::uuid() . '.' . $ext;
                Storage::disk('public')->put($filename, $response->body());

                // Update image_path di database
                $product->update(['image_path' => $filename]);

                $this->command->line("     ✓  Tersimpan: {$filename}");
                $berhasil++;

            } catch (\Exception $e) {
                $this->command->error("     Error: " . $e->getMessage());
                $gagal++;
            }
        }

        $this->command->newLine();
        $this->command->info("Selesai: {$berhasil} berhasil, {$gagal} gagal.");
    }
}

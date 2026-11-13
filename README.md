# Sistem Informasi Penjualan

Sistem Informasi Penjualan untuk toko **Blue Shop**, berbasis Laravel, untuk mengelola produk, transaksi kasir, pesanan online, laporan, dan pencetakan struk.

## Fitur

- Login dan pengelolaan akun dengan peran (role) pengguna
- Manajemen produk: nama, kategori, harga, stok, dan gambar
- Transaksi kasir dengan perhitungan total, jumlah dibayar, dan kembalian
- Pesanan online dengan data pelanggan (nama, telepon, alamat) dan metode pembayaran
- Struk transaksi yang bisa dicetak, untuk transaksi kasir maupun pesanan online
- Riwayat penjualan beserta detail item per transaksi

## Teknologi
## Screenshot

### Login

![Halaman login](docs/login.png)

### Dashboard

![Dashboard](docs/dashboard.png)

### Kasir

![Halaman kasir](docs/kasir.png)

### Struk

![Struk transaksi](docs/struk.png)

## ERD

![ERD sistem penjualan](docs/erd.png)

- Laravel 12
- PHP 8.2
- MySQL
- Vite dan Tailwind CSS
- Node.js dan npm

## Struktur Database

Tabel utama aplikasi:

| Tabel | Fungsi |
|-------|--------|
| `users` | Akun pengguna dan peran |
| `products` | Data produk dan stok |
| `sales` | Data transaksi (kasir atau online) |
| `sale_items` | Rincian barang pada setiap transaksi |

Relasi: `users` memiliki banyak `sales`, `sales` memiliki banyak `sale_items`, dan `products` dapat muncul di banyak `sale_items`. Pada pesanan online, kolom `user_id` di `sales` dapat kosong karena tidak ada kasir yang menangani.

Tabel lain (`sessions`, `cache`, `jobs`, `migrations`, dan sebagainya) adalah tabel bawaan Laravel.

## Cara Instalasi

1. Clone repository

   ```bash
   git clone https://github.com/windadotcom/sistem-penjualan.git
   cd sistem-penjualan
   ```

2. Install dependensi

   ```bash
   composer install
   npm install
   ```

3. Siapkan file environment

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Di Windows (PowerShell), gunakan `copy .env.example .env`.

4. Buat database `sistem_penjualan` di MySQL (misalnya lewat phpMyAdmin), lalu sesuaikan `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_penjualan
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Jalankan migrasi dan hubungkan folder storage

   ```bash
   php artisan migrate
   php artisan storage:link
   ```

6. Jalankan aplikasi pada dua terminal

   ```bash
   php artisan serve
   ```

   ```bash
   npm run dev
   ```

7. Buka `http://127.0.0.1:8000` di browser.

## Catatan

- Gunakan satu alamat secara konsisten (`127.0.0.1` atau `localhost`) agar sesi login tidak error 419 (Page Expired).
- Jika tampilan tidak berubah setelah mengedit CSS, jalankan `npm run build` atau pastikan `npm run dev` sedang berjalan.
- Jangan commit file `.env` karena berisi kunci aplikasi dan kredensial database.

## Lisensi

Proyek ini dibuat untuk keperluan pembelajaran. Framework Laravel berlisensi [MIT](https://opensource.org/licenses/MIT).

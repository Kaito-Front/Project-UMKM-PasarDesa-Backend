# Project UMKM Pasar Desa - Backend API

Backend Laravel 13 untuk aplikasi React di folder `frontend`. PHP Laragon 8.3 dan MySQL Laragon dipakai sebagai runtime lokal.

## Menjalankan di Laragon

Jalankan perintah PHP dan Composer dari Laragon Terminal agar keduanya tersedia di `PATH`.

1. Pastikan MySQL berjalan di Laragon.
2. Buat database `pasar_desa` di phpMyAdmin jika belum ada.
3. Salin `.env.example` menjadi `.env`, lalu sesuaikan host, port, nama database, dan kredensial MySQL.
4. Dari folder ini jalankan:

   ```bash
   composer install
   php artisan key:generate
   php artisan migrate --seed
   php artisan serve
   ```

API berjalan di `http://127.0.0.1:8000/api`. Frontend sudah memakai alamat tersebut secara default. Atur `VITE_API_URL` di `frontend/.env` bila alamat API berbeda.

## Endpoint

- `GET /api/categories`
- `GET /api/shops`
- `GET /api/products`
- `GET /api/products/{id}`
- `POST /api/products`
- `PATCH /api/products/{id}/visibility`
- `GET /api/orders`
- `POST /api/orders`
- `PATCH /api/orders/{id}/status`

Data kategori, toko, produk, dan pesanan awal dibuat oleh `php artisan db:seed`. Checkout menghitung total dari harga produk dalam database dan mengurangi stok dalam satu transaksi database.

Endpoint perubahan data belum memakai autentikasi; gunakan untuk demo lokal sampai login API ditambahkan.

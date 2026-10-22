<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['kuliner', 'Kuliner Tradisional', 'Utensils', 'Oleh-oleh, jajanan pasar, bumbu racik desa'],
            ['pertanian', 'Hasil Tani & Kebun', 'Sprout', 'Sayur segar dan hasil tani dari Sumedang'],
            ['kerajinan', 'Kerajinan Tangan', 'Palette', 'Anyaman bambu, tenun lurik, gerabah tanah liat'],
            ['kesehatan', 'Herbal & Jamu', 'HeartPulse', 'Madu hutan murni, jamu rempah, minyak kelapa dara'],
            ['fashion', 'Pakaian & Tenun', 'Shirt', 'Kain lurik ATBM, selendang tenun, kaos desa'],
        ];

        foreach ($categories as [$id, $name, $icon, $description]) {
            Category::updateOrCreate(['id' => $id], compact('name', 'icon', 'description'));
        }

        $shops = [
            ['id' => 'toko-1', 'name' => 'Griya Tenun Lurik Lestari', 'owner' => 'Ibu Sumarsih', 'category' => 'Kerajinan Tangan & Fashion', 'category_slug' => 'kerajinan', 'kecamatan' => 'Kecamatan Sumedang Utara', 'address' => 'Kecamatan Sumedang Utara, Kabupaten Sumedang, Jawa Barat', 'lat' => -6.8293742, 'lng' => 107.9116474, 'phone' => '081234567890', 'whatsapp' => '6281234567890', 'rating' => 4.9, 'review_count' => 142, 'join_date' => 'Januari 2024', 'operating_hours' => '08.00 - 17.00 WIB (Senin - Sabtu)', 'description' => 'Menjaga tradisi tenun lurik khas desa dengan alat tenun bukan mesin (ATBM).', 'avatar' => 'kain-lurik.jpg', 'banner' => 'kain-lurik.jpg', 'status' => 'aktif', 'verified' => true],
            ['id' => 'toko-2', 'name' => 'Kripik Tempe Bu Darmi', 'owner' => 'Ibu Darmiyah', 'category' => 'Kuliner Tradisional', 'category_slug' => 'kuliner', 'kecamatan' => 'Kecamatan Cimalaka', 'address' => 'Kecamatan Cimalaka, Kabupaten Sumedang, Jawa Barat', 'lat' => -6.8025187, 'lng' => 107.9413323, 'phone' => '085678901234', 'whatsapp' => '6285678901234', 'rating' => 4.8, 'review_count' => 98, 'join_date' => 'Maret 2024', 'operating_hours' => '07.00 - 20.00 WIB (Setiap Hari)', 'description' => 'Kripik tempe renyah gurih dengan daun jeruk asli dan racikan rempah tradisional.', 'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=200&q=80', 'banner' => 'https://images.unsplash.com/photo-1509722747041-616f39b57569?auto=format&fit=crop&w=1200&q=80', 'status' => 'aktif', 'verified' => true],
            ['id' => 'toko-3', 'name' => 'Madu Hutan & Gula Aren Tanjungsari', 'owner' => 'Pak Joko Sukirno', 'category' => 'Herbal & Jamu', 'category_slug' => 'kesehatan', 'kecamatan' => 'Kecamatan Tanjungsari', 'address' => 'Kecamatan Tanjungsari, Kabupaten Sumedang, Jawa Barat', 'lat' => -6.8440915, 'lng' => 107.7932319, 'phone' => '087812345678', 'whatsapp' => '6287812345678', 'rating' => 5.0, 'review_count' => 64, 'join_date' => 'Februari 2024', 'operating_hours' => '08.00 - 18.00 WIB (Senin - Minggu)', 'description' => 'Kelompok tani hutan memproduksi madu lebah liar murni dan gula aren.', 'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80', 'banner' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=1200&q=80', 'status' => 'aktif', 'verified' => true],
            ['id' => 'toko-4', 'name' => 'Anyaman Bambu Berkah Jaya', 'owner' => 'Pak Marto Wiyono', 'category' => 'Kerajinan Tangan', 'category_slug' => 'kerajinan', 'kecamatan' => 'Kecamatan Sumedang Selatan', 'address' => 'Kecamatan Sumedang Selatan, Kabupaten Sumedang, Jawa Barat', 'lat' => -6.859079, 'lng' => 107.9202767, 'phone' => '082198765432', 'whatsapp' => '6282198765432', 'rating' => 4.7, 'review_count' => 53, 'join_date' => 'April 2024', 'operating_hours' => '08.00 - 16.30 WIB (Senin - Sabtu)', 'description' => 'Keranjang, besek hampers, dan perabotan berbahan bambu apus pilihan.', 'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80', 'banner' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=1200&q=80', 'status' => 'aktif', 'verified' => true],
            ['id' => 'toko-5', 'name' => 'Kopi Robusta Jatinangor', 'owner' => 'Mas Hendra Pratama', 'category' => 'Kuliner Tradisional', 'category_slug' => 'kuliner', 'kecamatan' => 'Kecamatan Jatinangor', 'address' => 'Kecamatan Jatinangor, Kabupaten Sumedang, Jawa Barat', 'lat' => -6.9370992, 'lng' => 107.7813872, 'phone' => '089533344455', 'whatsapp' => '6289533344455', 'rating' => 4.9, 'review_count' => 110, 'join_date' => 'Mei 2024', 'operating_hours' => '09.00 - 21.00 WIB (Setiap Hari)', 'description' => 'Biji kopi robusta petik merah dengan aroma rempah khas tanah pegunungan desa.', 'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80', 'banner' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=1200&q=80', 'status' => 'menunggu', 'verified' => false],
        ];

        foreach ($shops as $shop) {
            Shop::updateOrCreate(['id' => $shop['id']], $shop);
        }

        $products = [
            ['id' => 'prod-1', 'name' => 'Kain Tenun Lurik Klasik Motif Telupat', 'price' => 185000, 'original_price' => 210000, 'category_id' => 'kerajinan', 'shop_id' => 'toko-1', 'stock' => 24, 'rating' => 4.9, 'sold' => 168, 'is_featured' => true, 'is_popular' => true, 'images' => ['kain-lurik.jpg', 'kain-lurik1.jpg', 'kain-lurik-2.jpg'], 'description' => 'Kain tenun lurik asli dengan motif telupat hitam abu kontemporer, ditenun menggunakan ATBM tradisional.', 'specifications' => [['label' => 'Bahan', 'value' => '100% Benang Katun Super'], ['label' => 'Ukuran', 'value' => '200 cm x 105 cm'], ['label' => 'Teknik', 'value' => 'ATBM (Tradisional Asli)']], 'reviews' => [['id' => 'rev-1', 'user' => 'Bambang Sudiro', 'rating' => 5, 'date' => '12 Okt 2026', 'comment' => 'Bahan luriknya sangat mantap dan adem. Pengerjaan rapi sekali!']]],
            ['id' => 'prod-2', 'name' => 'Kripik Tempe Renyah Gurih Daun Jeruk (250gr)', 'price' => 18000, 'original_price' => 20000, 'category_id' => 'kuliner', 'shop_id' => 'toko-2', 'stock' => 50, 'rating' => 4.8, 'sold' => 430, 'is_featured' => true, 'is_popular' => true, 'images' => ['https://images.unsplash.com/photo-1599490659213-e2b9527bd087?auto=format&fit=crop&w=800&q=80'], 'description' => 'Camilan khas desa dari kedelai pilihan dengan bumbu tradisional dan daun jeruk.', 'specifications' => [['label' => 'Berat Bersih', 'value' => '250 gram'], ['label' => 'Masa Simpan', 'value' => '3 bulan di wadah rapat']], 'reviews' => []],
            ['id' => 'prod-3', 'name' => 'Madu Hutan Liar Murni Akasia Lereng (500ml)', 'price' => 95000, 'original_price' => 110000, 'category_id' => 'kesehatan', 'shop_id' => 'toko-3', 'stock' => 18, 'rating' => 5, 'sold' => 89, 'is_featured' => true, 'is_new' => true, 'images' => ['https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=800&q=80'], 'description' => 'Madu hasil panen kelompok petani lebah di wilayah Tanjungsari.', 'specifications' => [['label' => 'Volume', 'value' => '500 ml'], ['label' => 'Kemasan', 'value' => 'Botol kaca food-grade']], 'reviews' => []],
            ['id' => 'prod-4', 'name' => 'Set Besek Anyaman Bambu Hampers Natural (Isi 3)', 'price' => 45000, 'original_price' => 50000, 'category_id' => 'kerajinan', 'shop_id' => 'toko-4', 'stock' => 35, 'rating' => 4.7, 'sold' => 120, 'is_popular' => true, 'images' => ['https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=800&q=80'], 'description' => 'Keranjang besek tradisional dari bambu apus untuk hantaran atau souvenir.', 'specifications' => [['label' => 'Isi Paket', 'value' => '3 ukuran (Kecil, Sedang, Besar)'], ['label' => 'Material', 'value' => 'Bambu Apus Lokal Pilihan']], 'reviews' => []],
            ['id' => 'prod-5', 'name' => 'Kopi Robusta Petik Merah Giling Medium (200gr)', 'price' => 38000, 'original_price' => 42000, 'category_id' => 'kuliner', 'shop_id' => 'toko-5', 'stock' => 40, 'rating' => 4.9, 'sold' => 95, 'is_popular' => true, 'is_new' => true, 'images' => ['https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=800&q=80'], 'description' => 'Biji kopi pilihan yang dipetik saat matang merah di perkebunan rakyat lereng desa.', 'specifications' => [['label' => 'Varietas', 'value' => 'Robusta Sumedang'], ['label' => 'Proses', 'value' => 'Natural Process']], 'reviews' => []],
            ['id' => 'prod-6', 'name' => 'Gula Aren Semut Organik Asli Cetak Batang (1kg)', 'price' => 32000, 'original_price' => 35000, 'category_id' => 'kuliner', 'shop_id' => 'toko-3', 'stock' => 30, 'rating' => 4.9, 'sold' => 145, 'is_featured' => true, 'is_popular' => true, 'images' => ['https://images.unsplash.com/photo-1621996346565-e3d5d6281782?auto=format&fit=crop&w=800&q=80'], 'description' => 'Nira aren murni yang dimasak perlahan di atas tungku kayu bakar tradisional.', 'specifications' => [['label' => 'Komposisi', 'value' => '100% Nira Aren Murni'], ['label' => 'Bentuk', 'value' => 'Butiran Semut Kering']], 'reviews' => []],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['id' => $product['id']], $product);
        }

        $orders = [
            ['id' => 'ORD-2026-081', 'product_name' => 'Kain Tenun Lurik Klasik Motif Telupat', 'buyer_name' => 'Rudi Hartono', 'phone' => '081399887766', 'total' => 185000, 'qty' => 1, 'status' => 'diproses', 'shop_name' => 'Griya Tenun Lurik Lestari', 'kecamatan' => 'Kecamatan Sumedang Utara', 'address' => 'Kecamatan Sumedang Utara, Kabupaten Sumedang, Jawa Barat', 'items' => [], 'shipping_method' => 'Kurir Kilat Desa', 'payment_method' => 'Bayar di Tempat (COD)', 'notes' => ''],
            ['id' => 'ORD-2026-080', 'product_name' => 'Kripik Tempe Renyah Gurih Daun Jeruk (250gr)', 'buyer_name' => 'Dewi Lestari', 'phone' => '082211445566', 'total' => 36000, 'qty' => 2, 'status' => 'dikirim', 'shop_name' => 'Kripik Tempe Bu Darmi', 'kecamatan' => 'Kecamatan Cimalaka', 'address' => 'Komplek Balai Desa RT 01', 'items' => [], 'shipping_method' => 'Kurir Kilat Desa', 'payment_method' => 'Bayar di Tempat (COD)', 'notes' => ''],
            ['id' => 'ORD-2026-079', 'product_name' => 'Madu Hutan Liar Murni Akasia Lereng (500ml)', 'buyer_name' => 'Ahmad Fauzi', 'phone' => '085733221100', 'total' => 95000, 'qty' => 1, 'status' => 'selesai', 'shop_name' => 'Madu Hutan & Gula Aren Tanjungsari', 'kecamatan' => 'Kecamatan Tanjungsari', 'address' => 'Pondok Pesantren Nurul Huda', 'items' => [], 'shipping_method' => 'Kurir Kilat Desa', 'payment_method' => 'Bayar di Tempat (COD)', 'notes' => ''],
        ];

        foreach ($orders as $order) {
            Order::updateOrCreate(['id' => $order['id']], $order);
        }
    }
}

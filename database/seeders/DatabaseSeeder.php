<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(MarketplaceSeeder::class);

        User::updateOrCreate(['email' => 'admin@pasardesa.go.id'], [
            'name' => 'Admin Pasar Desa', 'phone' => '081122334455', 'password' => 'admin123', 'role' => 'admin',
        ]);
        User::updateOrCreate(['email' => 'sumarsih@pasardesa.id'], [
            'name' => 'Ibu Sumarsih', 'phone' => '081234567890', 'password' => 'password123', 'role' => 'keduanya',
            'shop_id' => 'toko-1', 'shop_name' => 'Griya Tenun Lurik Lestari', 'kecamatan' => 'Kecamatan Sumedang Utara',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@temujasa.id'],
            [
                'name' => 'Administrator',
                'phone' => null,
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Tidak ada data dummy/contoh — kategori & jasa diisi langsung oleh pengguna
        // melalui aplikasi (admin/penyedia), bukan oleh seeder.
    }
}
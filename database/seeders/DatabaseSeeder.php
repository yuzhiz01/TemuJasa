<?php

namespace Database\Seeders;

use App\Models\Category;
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

        // Kategori Jasa
        $categories = [
            'Elektronik',
            'Otomotif',
            'Rumah Tangga',
            'Kebersihan',
            'AC & Pendingin',
            'Renovasi & Perbaikan',
            'Kecantikan & Kesehatan',
            'Acara & Event',
            'Pendidikan & Les',
            'Lainnya',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
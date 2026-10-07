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
        // Seeder Profil Sekolah SMP Muhammadiyah Tonjong
        $this->call([
            SchoolProfileSeeder::class,
        ]);

        // User bawaan jika diperlukan (opsional)
        // User::factory()->create([
        //     'name' => 'Admin Spemto',
        //     'email' => 'smpmuhitonjong@gmail.com',
        // ]);
    }
}
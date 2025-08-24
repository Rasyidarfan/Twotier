<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin default
        User::create([
            'name' => 'Administrator',
            'email' => 'aarfanarsyad@bps.go.id',
            'password' => '$2y$10$9Lr9E7sAM22dN/990YaHae2ezSWeTCMilXQVul15vX8Pq7XwEJHxa', // password: admin123
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Guru-guru yang akan membuat soal
        User::create([
            'name' => 'Ustadz Ahmad Fadhil',
            'email' => 'ahmad.fadhil@madrasah.edu',
            'password' => Hash::make('patrickstar'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Ustadzah Siti Khadijah',
            'email' => 'siti.khadijah@madrasah.edu',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Ustadz Muhammad Ridwan',
            'email' => 'muhammad.ridwan@madrasah.edu',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        // Admin tambahan
        User::create([
            'name' => 'Kepala Madrasah',
            'email' => 'kepala@madrasah.edu',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Guru tambahan
        User::create([
            'name' => 'Ustadzah Aisyah',
            'email' => 'aisyah@madrasah.edu',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'is_active' => true,
        ]);
    }
}

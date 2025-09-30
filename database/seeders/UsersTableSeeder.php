<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'aarfanarsyad@bps.go.id',
                'email_verified_at' => NULL,
                'password' => '$2y$10$9Lr9E7sAM22dN/990YaHae2ezSWeTCMilXQVul15vX8Pq7XwEJHxa',
                'role' => 'admin',
                'is_active' => 1,
                'timezone' => 'Asia/Jakarta',
                'remember_token' => NULL,
                'created_at' => '2025-09-23 15:42:19',
                'updated_at' => '2025-09-23 15:42:19',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Siti Zulfa Hidayatul Maula',
                'email' => 'szulfa.hm@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$/FnItsWH9gfFgR4GfH84r.GqYm7ezZWUDn1TWYtdXnWLtXLmAR5XS',
                'role' => 'guru',
                'is_active' => 1,
                'timezone' => 'Asia/Jakarta',
                'remember_token' => NULL,
                'created_at' => '2025-09-23 15:42:19',
                'updated_at' => '2025-09-29 15:40:24',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Ustadz Makki',
                'email' => 'ustadz.makki@gmail.com',
                'email_verified_at' => NULL,
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'is_active' => 1,
                'timezone' => 'Asia/Jakarta',
                'remember_token' => NULL,
                'created_at' => '2025-09-23 15:42:19',
                'updated_at' => '2025-09-29 15:47:01',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Ustadz Muhammad Ridwan',
                'email' => 'muhammad.ridwan@madrasah.edu',
                'email_verified_at' => NULL,
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'is_active' => 0,
                'timezone' => 'Asia/Jakarta',
                'remember_token' => NULL,
                'created_at' => '2025-09-23 15:42:19',
                'updated_at' => '2025-09-29 15:44:24',
            ),
        ));
        
        
    }
}
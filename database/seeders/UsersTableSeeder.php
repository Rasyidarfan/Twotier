<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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

        \DB::table('users')->insert(array(
            0 =>
                array(
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
                array(
                    'id' => 2,
                    'name' => 'Ustadzah Hasaniyah',
                    'email' => 'ustadzah.hasaniyah@gmail.com',
                    'email_verified_at' => NULL,
                    'password' => '$2y$12$Dypsm9pNvtYmMFwBJAi3g.MaW2AFA4Tg1anFEXR6nP2HCuAnQMhEa',
                    'role' => 'guru',
                    'is_active' => 1,
                    'timezone' => 'Asia/Jakarta',
                    'remember_token' => '2errFmpIytXey07XN7G9l79w9NRIi2YZQtKbH0fr9nSu7Wl6DzhrrRuMi6qE',
                    'created_at' => '2025-09-23 15:42:19',
                    'updated_at' => '2025-11-03 14:07:21',
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => 'Siti Zulfa Hidayatul Maula',
                    'email' => 'szulfa.hm@gmail.com',
                    'email_verified_at' => NULL,
                    'password' => '$2y$12$xrKH5bUWt6CCSooJV.PVgev2wqsHF9D5O8uz0sX8GNK.jeX0uhNHK',
                    'role' => 'guru',
                    'is_active' => 1,
                    'timezone' => 'Asia/Jakarta',
                    'remember_token' => NULL,
                    'created_at' => '2025-09-23 15:42:19',
                    'updated_at' => '2025-11-03 14:08:05',
                ),
            3 =>
                array(
                    'id' => 4,
                    'name' => 'Ustadzah Rifa',
                    'email' => 'ustadzah.rifa@gmail.com',
                    'email_verified_at' => NULL,
                    'password' => '$2y$12$4waxewoAnZ/N7133ieLwp..5h8XxiWBWvsgENt2Guos5XrxyZXSyS',
                    'role' => 'guru',
                    'is_active' => 1,
                    'timezone' => 'Asia/Jakarta',
                    'remember_token' => NULL,
                    'created_at' => '2025-09-23 15:42:19',
                    'updated_at' => '2025-10-05 09:37:15',
                ),
        ));


    }
}
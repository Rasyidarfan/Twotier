<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('subjects')->delete();
        
        \DB::table('subjects')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Bahasa Arab',
                'code' => 'BA',
                'description' => 'Mata Pelajaran Bahasa Arab untuk Madrasah Aliyah',
                'is_active' => 1,
                'created_at' => '2025-09-23 15:42:20',
                'updated_at' => '2025-09-28 19:39:42',
            ),
        ));
        
        
    }
}
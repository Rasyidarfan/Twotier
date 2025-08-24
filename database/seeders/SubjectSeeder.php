<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        Subject::create([
            'name' => 'Bahasa Arab',
            'code' => 'BA',
            'description' => 'Mata pelajaran Bahasa Arab untuk Madrasah Aliyah',
            'is_active' => true,
        ]);
    }
}

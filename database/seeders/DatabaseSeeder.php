<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SubjectSeeder::class,
            ChapterSeeder::class,
            QuestionSeederKelasX::class,
            QuestionSeederKelasXI::class,
            QuestionSeederKelasXII::class,
            ExamSeeder::class,
            ExamQuestionSeeder::class,
        ]);
    }
}

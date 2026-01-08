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
            UsersTableSeeder::class,
            SubjectsTableSeeder::class,
            ChaptersTableSeeder::class,
            QuestionsTableSeeder::class,
            ExamsTableSeeder::class,
            ExamQuestionsTableSeeder::class,
            ExamSessionsTableSeeder::class,
            StudentExamSessionsTableSeeder::class,
            StudentAnswersTableSeeder::class,
            ExamResultsTableSeeder::class,
        ]);
    }
}

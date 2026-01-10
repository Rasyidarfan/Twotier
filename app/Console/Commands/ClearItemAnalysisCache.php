<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearItemAnalysisCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item-analysis:clear-cache {exam_id? : The exam ID to clear cache for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear item analysis cache for specific exam or all exams';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $examId = $this->argument('exam_id');

        if ($examId) {
            // Clear specific exam cache
            Cache::forget("item_analysis_{$examId}");
            $this->info("✅ Cache cleared for exam ID: {$examId}");
        } else {
            // Clear all item analysis cache
            $exams = \App\Models\Exam::all();
            $count = 0;

            foreach ($exams as $exam) {
                Cache::forget("item_analysis_{$exam->id}");
                $count++;
            }

            $this->info("✅ Cache cleared for {$count} exams");
        }

        // Also clear application cache
        $this->call('cache:clear');

        return Command::SUCCESS;
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamQuestion;

class ExamQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->assignQuestionsToExams();
    }

    private function assignQuestionsToExams()
    {
        // Ambil semua ujian
        $exams = Exam::all();

        foreach ($exams as $exam) {
            // Ambil soal berdasarkan grade dan semester melalui relationship chapter
            $questions = Question::whereHas('chapter', function ($query) use ($exam) {
                $query->where('grade', $exam->grade)
                      ->where('semester', $exam->semester);
            })
            ->where('is_active', true)
            ->get();

            if ($questions->count() > 0) {
                $this->assignQuestionsToExam($exam, $questions);
            }
        }
    }

    private function assignQuestionsToExam($exam, $questions)
    {
        // Hapus assignment yang sudah ada untuk exam ini
        ExamQuestion::where('exam_id', $exam->id)->delete();
        
        // Tentukan jumlah soal berdasarkan durasi ujian
        $questionCount = $this->determineQuestionCount($exam->duration_minutes);
        
        // Ambil soal secara random sesuai jumlah yang ditentukan
        $selectedQuestions = $questions->shuffle()->take($questionCount);

        $order = 1;
        foreach ($selectedQuestions as $question) {
            ExamQuestion::create([
                'exam_id' => $exam->id,
                'question_id' => $question->id,
                'question_order' => $order,
                'points' => $this->determineQuestionPoints($question->difficulty),
            ]);
            $order++;
        }
    }

    private function determineQuestionCount($duration)
    {
        // Estimasi 4-5 menit per soal two-tier (lebih lama karena ada 2 tier)
        if ($duration <= 30) {
            return 5;  // Ujian singkat
        } elseif ($duration <= 45) {
            return 10; // Ujian standar Kelas X
        } elseif ($duration <= 60) {
            return 12; // Ujian sedang Kelas XI
        } elseif ($duration <= 90) {
            return 15; // Ujian panjang Kelas XII
        } else {
            return 20; // Ujian komprehensif
        }
    }

    private function determineQuestionPoints($difficulty)
    {
        // Poin berdasarkan tingkat kesulitan
        switch ($difficulty) {
            case 'mudah':
                return 8;
            case 'sedang':
                return 10;
            case 'sulit':
                return 12;
            default:
                return 10;
        }
    }

    /**
     * Assignment khusus untuk memastikan distribusi soal yang baik
     */
    private function assignBalancedQuestions()
    {
        $exams = Exam::all();

        foreach ($exams as $exam) {
            // Skip jika sudah ada assignment
            if ($exam->examQuestions()->count() > 0) {
                continue;
            }

            // Ambil soal berdasarkan chapter untuk distribusi yang merata
            $chapters = \App\Models\Chapter::where('grade', $exam->grade)
                                          ->where('semester', $exam->semester)
                                          ->where('is_active', true)
                                          ->get();

            $selectedQuestions = collect();
            $targetCount = $this->determineQuestionCount($exam->duration_minutes);
            $questionsPerChapter = max(1, floor($targetCount / $chapters->count()));

            foreach ($chapters as $chapter) {
                $chapterQuestions = Question::where('chapter_id', $chapter->id)
                                          ->where('is_active', true)
                                          ->take($questionsPerChapter)
                                          ->get();
                
                $selectedQuestions = $selectedQuestions->merge($chapterQuestions);
            }

            // Jika kurang dari target, ambil soal tambahan secara random
            if ($selectedQuestions->count() < $targetCount) {
                $remainingCount = $targetCount - $selectedQuestions->count();
                $additionalQuestions = Question::whereHas('chapter', function ($query) use ($exam) {
                    $query->where('grade', $exam->grade)
                          ->where('semester', $exam->semester);
                })
                ->where('is_active', true)
                ->whereNotIn('id', $selectedQuestions->pluck('id'))
                ->take($remainingCount)
                ->get();

                $selectedQuestions = $selectedQuestions->merge($additionalQuestions);
            }

            // Shuffle dan assign
            $selectedQuestions = $selectedQuestions->shuffle()->take($targetCount);

            $order = 1;
            foreach ($selectedQuestions as $question) {
                ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'question_id' => $question->id,
                    'question_order' => $order,
                    'points' => $this->determineQuestionPoints($question->difficulty),
                ]);
                $order++;
            }
        }
    }
}

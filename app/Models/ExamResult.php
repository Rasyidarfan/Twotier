<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_name',
        'student_identifier',
        'started_at',
        'finished_at',
        'correct_correct',
        'correct_wrong',
        'wrong_correct',
        'wrong_wrong',
        'total_score',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'total_score' => 'integer',
        'correct_correct' => 'integer',
        'correct_wrong' => 'integer',
        'wrong_correct' => 'integer',
        'wrong_wrong' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'result_id');
    }

    // Get total questions answered
    public function getTotalAnsweredAttribute()
    {
        return $this->correct_correct + $this->correct_wrong + $this->wrong_correct + $this->wrong_wrong;
    }

    // Get total questions in exam
    public function getTotalQuestionsAttribute()
    {
        return $this->exam->examQuestions()->count();
    }

    // Calculate score based on two-tier methodology
    public function calculateScore()
    {
        $totalQuestions = $this->total_questions;
        
        if ($totalQuestions == 0) {
            return 0;
        }

        // Two-tier scoring system: 
        // Benar-Benar = 100% (full points)
        // Benar-Salah = 50% (partial understanding)
        // Salah-Benar = 25% (lucky guess)
        // Salah-Salah = 0% (no understanding)
        
        $basePoints = 10; // Base points per question
        $totalScore = ($this->correct_correct * $basePoints) + 
                      ($this->correct_wrong * ($basePoints * 0.5)) + 
                      ($this->wrong_correct * ($basePoints * 0.25)) + 
                      ($this->wrong_wrong * 0);

        $maxScore = $totalQuestions * $basePoints;

        return $maxScore > 0 ? round(($totalScore / $maxScore) * 100, 2) : 0;
    }

    // Update score and breakdown
    public function updateScore()
    {
        $this->total_score = $this->calculateScore();
        $this->save();
    }

    // Calculate breakdown from student answers
    public function calculateBreakdown()
    {
        $answers = $this->studentAnswers()->with('question')->get();
        
        $breakdown = [
            'correct_correct' => 0,
            'correct_wrong' => 0,
            'wrong_correct' => 0,
            'wrong_wrong' => 0,
        ];

        foreach ($answers as $answer) {
            if ($answer->tier1_answer !== null && $answer->tier2_answer !== null) {
                $tier1Correct = $answer->tier1_answer === $answer->question->tier1_correct_answer;
                $tier2Correct = $answer->tier2_answer === $answer->question->tier2_correct_answer;

                if ($tier1Correct && $tier2Correct) {
                    $breakdown['correct_correct']++;
                } elseif ($tier1Correct && !$tier2Correct) {
                    $breakdown['correct_wrong']++;
                } elseif (!$tier1Correct && $tier2Correct) {
                    $breakdown['wrong_correct']++;
                } else {
                    $breakdown['wrong_wrong']++;
                }
            }
        }

        $this->update($breakdown);
        $this->updateScore();
    }

    // Get percentage for each category
    public function getCorrectCorrectPercentageAttribute()
    {
        return $this->total_questions > 0 ? round(($this->correct_correct / $this->total_questions) * 100, 1) : 0;
    }

    public function getCorrectWrongPercentageAttribute()
    {
        return $this->total_questions > 0 ? round(($this->correct_wrong / $this->total_questions) * 100, 1) : 0;
    }

    public function getWrongCorrectPercentageAttribute()
    {
        return $this->total_questions > 0 ? round(($this->wrong_correct / $this->total_questions) * 100, 1) : 0;
    }

    public function getWrongWrongPercentageAttribute()
    {
        return $this->total_questions > 0 ? round(($this->wrong_wrong / $this->total_questions) * 100, 1) : 0;
    }

    // Status checks
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isTimeout()
    {
        return $this->status === 'timeout';
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }
}

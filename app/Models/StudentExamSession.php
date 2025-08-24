<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExamSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_name',
        'student_identifier',
        'started_at',
        'finished_at',
        'total_score',
        'scoring_breakdown',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'scoring_breakdown' => 'array',
        'total_score' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'session_id');
    }

    // Start session
    public function startSession()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    // Finish session
    public function finishSession()
    {
        $this->calculateScore();
        $this->update([
            'status' => 'finished',
            'finished_at' => now(),
        ]);
    }

    // Calculate final score using two-tier methodology
    public function calculateScore()
    {
        $answers = $this->studentAnswers()->with('question')->get();
        
        $breakdown = [
            'benar-benar' => 0,
            'benar-salah' => 0,
            'salah-benar' => 0,
            'salah-salah' => 0,
        ];

        $totalScore = 0;

        foreach ($answers as $answer) {
            if ($answer->tier1_answer !== null && $answer->tier2_answer !== null) {
                $category = $answer->question->evaluateAnswer(
                    $answer->tier1_answer, 
                    $answer->tier2_answer
                );
                
                $examQuestion = $this->exam->examQuestions()
                    ->where('question_id', $answer->question_id)
                    ->first();
                
                $basePoints = $examQuestion ? $examQuestion->points : 10;
                $points = $answer->question->calculatePoints($category, $basePoints);
                
                $answer->update([
                    'result_category' => $category,
                    'points_earned' => $points,
                ]);
                
                $breakdown[$category]++;
                $totalScore += $points;
            }
        }

        $this->update([
            'total_score' => $totalScore,
            'scoring_breakdown' => $breakdown,
        ]);

        return $totalScore;
    }

    // Check if session is expired
    public function isExpired()
    {
        if (!$this->started_at || !$this->exam || $this->status === 'finished') {
            return false;
        }

        $endTime = $this->started_at->addMinutes($this->exam->duration_minutes);
        return now()->gt($endTime);
    }

    // Get remaining time in seconds
    public function getRemainingTimeAttribute()
    {
        if (!$this->started_at || !$this->exam || $this->status === 'finished') {
            return 0;
        }

        $endTime = $this->started_at->copy()->addMinutes($this->exam->duration_minutes);
        $remaining = $endTime->diffInSeconds(now(), false);
        
        // If remaining is negative, time is up
        return max(0, $remaining);
    }

    // Get progress percentage
    public function getProgressAttribute()
    {
        $totalQuestions = $this->exam->total_questions ?? 0;
        if ($totalQuestions === 0) {
            return 0;
        }

        $answeredQuestions = $this->studentAnswers()
            ->whereNotNull('tier1_answer')
            ->whereNotNull('tier2_answer')
            ->count();
        
        return round(($answeredQuestions / $totalQuestions) * 100, 1);
    }

    // Get total answered questions
    public function getTotalAnsweredAttribute()
    {
        return $this->studentAnswers()
            ->whereNotNull('tier1_answer')
            ->whereNotNull('tier2_answer')
            ->count();
    }

    // Get score percentage
    public function getScorePercentageAttribute()
    {
        $maxScore = $this->exam->total_points ?? 0;
        if ($maxScore === 0) {
            return 0;
        }

        return round(($this->total_score / $maxScore) * 100, 1);
    }

    // Get duration in minutes
    public function getDurationAttribute()
    {
        if (!$this->started_at || !$this->finished_at) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->finished_at);
    }

    // Check if all questions are answered
    public function isComplete()
    {
        $totalQuestions = $this->exam->total_questions ?? 0;
        return $this->total_answered >= $totalQuestions;
    }

    // Auto finish if expired
    public function checkAndFinishIfExpired()
    {
        if ($this->isExpired() && $this->status === 'in_progress') {
            $this->update([
                'status' => 'timeout',
                'finished_at' => now(),
            ]);
            $this->calculateScore();
        }
    }

    // Get breakdown summary
    public function getBreakdownSummaryAttribute()
    {
        $breakdown = $this->scoring_breakdown ?? [];
        
        return [
            'benar_benar' => $breakdown['benar-benar'] ?? 0,
            'benar_salah' => $breakdown['benar-salah'] ?? 0,
            'salah_benar' => $breakdown['salah-benar'] ?? 0,
            'salah_salah' => $breakdown['salah-salah'] ?? 0,
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeFinished($query)
    {
        return $query->whereIn('status', ['finished', 'timeout']);
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}

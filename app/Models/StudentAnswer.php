<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'question_id',
        'tier1_answer',
        'tier2_answer',
        'result_category',
        'points_earned',
        'result_id', // For backward compatibility with ExamResult
    ];

    protected $casts = [
        'tier1_answer' => 'integer',
        'tier2_answer' => 'integer',
        'points_earned' => 'integer',
    ];

    // Relationships
    public function session()
    {
        return $this->belongsTo(StudentExamSession::class, 'session_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function examResult()
    {
        return $this->belongsTo(ExamResult::class, 'result_id');
    }

    // Check if both tiers are answered
    public function isComplete()
    {
        return $this->tier1_answer !== null && $this->tier2_answer !== null;
    }

    // Check if only tier 1 is answered
    public function isTier1Only()
    {
        return $this->tier1_answer !== null && $this->tier2_answer === null;
    }

    // Check if no answers provided
    public function isEmpty()
    {
        return $this->tier1_answer === null && $this->tier2_answer === null;
    }

    // Get tier 1 answer text
    public function getTier1AnswerTextAttribute()
    {
        if ($this->tier1_answer === null || !$this->question) {
            return null;
        }

        $options = $this->question->tier1_options;
        return isset($options[$this->tier1_answer]) ? $options[$this->tier1_answer] : null;
    }

    // Get tier 2 answer text
    public function getTier2AnswerTextAttribute()
    {
        if ($this->tier2_answer === null || !$this->question) {
            return null;
        }

        $options = $this->question->tier2_options;
        return isset($options[$this->tier2_answer]) ? $options[$this->tier2_answer] : null;
    }

    // Check if tier 1 is correct
    public function getIsTier1CorrectAttribute(): bool
    {
        if ($this->tier1_answer === null || !$this->question) {
            return false;
        }
        
        return $this->tier1_answer === $this->question->tier1_correct_answer;
    }

    // Check if tier 2 is correct
    public function getIsTier2CorrectAttribute(): bool
    {
        if ($this->tier2_answer === null || !$this->question) {
            return false;
        }
        
        return $this->tier2_answer === $this->question->tier2_correct_answer;
    }

    // Get correct answers for comparison
    public function getTier1CorrectAnswerTextAttribute()
    {
        return $this->question ? $this->question->getTier1CorrectAnswerText() : null;
    }

    public function getTier2CorrectAnswerTextAttribute()
    {
        return $this->question ? $this->question->getTier2CorrectAnswerText() : null;
    }

    // Calculate points for this answer
    public function calculatePoints()
    {
        if (!$this->isComplete() || !$this->question) {
            return 0;
        }

        $category = $this->question->evaluateAnswer($this->tier1_answer, $this->tier2_answer);
        
        // Get base points from exam question or use default
        $basePoints = 10;
        if ($this->session && $this->session->exam) {
            $examQuestion = $this->session->exam->examQuestions()
                ->where('question_id', $this->question_id)
                ->first();
            if ($examQuestion) {
                $basePoints = $examQuestion->points;
            }
        }

        $points = $this->question->calculatePoints($category, $basePoints);
        
        // Update the answer with category and points
        $this->update([
            'result_category' => $category,
            'points_earned' => $points,
        ]);

        return $points;
    }

    // Get answer status for display
    public function getAnswerStatusAttribute()
    {
        if (!$this->isComplete()) {
            return 'incomplete';
        }

        if ($this->is_tier1_correct && $this->is_tier2_correct) {
            return 'benar-benar';
        } elseif ($this->is_tier1_correct && !$this->is_tier2_correct) {
            return 'benar-salah';
        } elseif (!$this->is_tier1_correct && $this->is_tier2_correct) {
            return 'salah-benar';
        } else {
            return 'salah-salah';
        }
    }

    // Get points percentage
    public function getPointsPercentageAttribute()
    {
        if (!$this->question) {
            return 0;
        }

        $basePoints = 10;
        if ($this->session && $this->session->exam) {
            $examQuestion = $this->session->exam->examQuestions()
                ->where('question_id', $this->question_id)
                ->first();
            if ($examQuestion) {
                $basePoints = $examQuestion->points;
            }
        }

        return $basePoints > 0 ? round(($this->points_earned / $basePoints) * 100, 1) : 0;
    }

    // Submit answer for tier 1
    public function submitTier1Answer($answerIndex)
    {
        if ($this->question && $this->question->isValidAnswerIndex(1, $answerIndex)) {
            $this->update(['tier1_answer' => $answerIndex]);
            return true;
        }
        return false;
    }

    // Submit answer for tier 2
    public function submitTier2Answer($answerIndex)
    {
        if ($this->question && $this->question->isValidAnswerIndex(2, $answerIndex)) {
            $this->update(['tier2_answer' => $answerIndex]);
            
            // Calculate points if both tiers are now complete
            if ($this->isComplete()) {
                $this->calculatePoints();
            }
            
            return true;
        }
        return false;
    }

    // Clear answers
    public function clearAnswers()
    {
        $this->update([
            'tier1_answer' => null,
            'tier2_answer' => null,
            'result_category' => null,
            'points_earned' => 0,
        ]);
    }

    // Scopes
    public function scopeComplete($query)
    {
        return $query->whereNotNull('tier1_answer')
                     ->whereNotNull('tier2_answer');
    }

    public function scopeIncomplete($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('tier1_answer')
              ->orWhereNull('tier2_answer');
        });
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('result_category', $category);
    }

    public function scopeCorrect($query)
    {
        return $query->where('result_category', 'benar-benar');
    }

    public function scopePartiallyCorrect($query)
    {
        return $query->whereIn('result_category', ['benar-salah', 'salah-benar']);
    }

    public function scopeIncorrect($query)
    {
        return $query->where('result_category', 'salah-salah');
    }
}

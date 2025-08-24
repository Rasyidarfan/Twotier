<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'tier1_question',
        'tier1_options',
        'tier1_correct_answer',
        'tier2_question',
        'tier2_options',
        'tier2_correct_answer',
        'difficulty',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'tier1_options' => 'array',
        'tier2_options' => 'array',
        'tier1_correct_answer' => 'integer',
        'tier2_correct_answer' => 'integer',
        'is_active' => 'boolean',
    ];

    // Defensive accessors to handle malformed data
    public function getTier1OptionsAttribute($value)
    {
        // If it's already an array, return it
        if (is_array($value)) {
            return $value;
        }

        // If it's a string, try to decode it
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
            
            // Fallback: split by comma if JSON decode fails
            $fallback = explode(',', trim($value, '"'));
            return count($fallback) > 1 ? array_map('trim', $fallback) : [];
        }

        // Return empty array as last resort
        return [];
    }

    public function getTier2OptionsAttribute($value)
    {
        // If it's already an array, return it
        if (is_array($value)) {
            return $value;
        }

        // If it's a string, try to decode it
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
            
            // Fallback: split by comma if JSON decode fails
            $fallback = explode(',', trim($value, '"'));
            return count($fallback) > 1 ? array_map('trim', $fallback) : [];
        }

        // Return empty array as last resort
        return [];
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByChapter($query, $chapterId)
    {
        return $query->where('chapter_id', $chapterId);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function scopeByGradeAndSemester($query, $grade, $semester)
    {
        return $query->whereHas('chapter', function ($q) use ($grade, $semester) {
            $q->where('grade', $grade)->where('semester', $semester);
        });
    }

    // Helper methods untuk evaluasi jawaban two-tier
    public function evaluateAnswer($tier1Answer, $tier2Answer)
    {
        $tier1Correct = (int)$tier1Answer === $this->tier1_correct_answer;
        $tier2Correct = (int)$tier2Answer === $this->tier2_correct_answer;

        if ($tier1Correct && $tier2Correct) {
            return 'benar-benar';
        } elseif ($tier1Correct && !$tier2Correct) {
            return 'benar-salah';
        } elseif (!$tier1Correct && $tier2Correct) {
            return 'salah-benar';
        } else {
            return 'salah-salah';
        }
    }

    // Helper method untuk menghitung poin berdasarkan kategori two-tier
    public function calculatePoints($category, $basePoints = 10)
    {
        $multipliers = [
            'benar-benar' => 1.0,    // 100% - Pemahaman konsep dan reasoning benar
            'benar-salah' => 0.5,    // 50%  - Konsep benar tapi reasoning salah
            'salah-benar' => 0.25,   // 25%  - Konsep salah tapi reasoning benar (mungkin kebetulan)
            'salah-salah' => 0.0,    // 0%   - Konsep dan reasoning salah
        ];

        return (int) ($basePoints * $multipliers[$category]);
    }

    // Get formatted options untuk display
    public function getFormattedTier1Options()
    {
        $options = $this->tier1_options;
        $formatted = [];
        
        foreach ($options as $index => $option) {
            $formatted[] = [
                'index' => $index,
                'text' => $option,
                'is_correct' => $index === $this->tier1_correct_answer
            ];
        }
        
        return $formatted;
    }

    public function getFormattedTier2Options()
    {
        $options = $this->tier2_options;
        $formatted = [];
        
        foreach ($options as $index => $option) {
            $formatted[] = [
                'index' => $index,
                'text' => $option,
                'is_correct' => $index === $this->tier2_correct_answer
            ];
        }
        
        return $formatted;
    }

    // Get correct answer text
    public function getTier1CorrectAnswerText()
    {
        return $this->tier1_options[$this->tier1_correct_answer] ?? null;
    }

    public function getTier2CorrectAnswerText()
    {
        return $this->tier2_options[$this->tier2_correct_answer] ?? null;
    }

    // Validation helper
    public function isValidAnswerIndex($tier, $answerIndex)
    {
        $options = $tier === 1 ? $this->tier1_options : $this->tier2_options;
        return is_integer($answerIndex) && $answerIndex >= 0 && $answerIndex < count($options);
    }
}

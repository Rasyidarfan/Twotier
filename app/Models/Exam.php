<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'code',
        'subject_id',
        'grade',
        'semester',
        'duration_minutes',
        'start_time',
        'end_time',
        'status',
        'shuffle_questions',
        'show_result_immediately',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'shuffle_questions' => 'boolean',
        'show_result_immediately' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($exam) {
            if (empty($exam->code)) {
                $exam->code = $exam->generateUniqueCode();
            }
        });
    }

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class)->orderBy('question_order');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
            ->withPivot('question_order', 'points')
            ->orderBy('exam_questions.question_order');
    }

    public function studentSessions()
    {
        return $this->hasMany(StudentExamSession::class);
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    // Get questions in correct order
    public function getOrderedQuestions()
    {
        return $this->questions()->orderBy('exam_questions.question_order')->get();
    }

    // Get questions for student (shuffled if enabled)
    public function getQuestionsForStudent()
    {
        $questions = $this->getOrderedQuestions();
        
        if ($this->shuffle_questions) {
            return $questions->shuffle();
        }
        
        return $questions;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeFinished($query)
    {
        return $query->where('status', 'finished');
    }

    public function scopeByGradeAndSemester($query, $grade, $semester)
    {
        return $query->where('grade', $grade)->where('semester', $semester);
    }

    public function scopeByCreator($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    // Generate kode ujian unik 6 karakter
    private function generateUniqueCode()
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (self::where('code', $code)->exists());
        
        return $code;
    }

    // Status checks
    public function isActive()
    {
        return $this->status === 'active' && 
               now()->between($this->start_time, $this->end_time);
    }

    public function canStart()
    {
        return $this->status === 'waiting' || 
               ($this->status === 'active' && now()->gte($this->start_time));
    }

    public function isExpired()
    {
        return $this->end_time && now()->gt($this->end_time);
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isFinished()
    {
        return $this->status === 'finished';
    }

    // Get total questions
    public function getTotalQuestionsAttribute()
    {
        return $this->examQuestions()->count();
    }

    // Get total points
    public function getTotalPointsAttribute()
    {
        return $this->examQuestions()->sum('points');
    }

    // Get average points per question
    public function getAveragePointsAttribute()
    {
        $total = $this->total_questions;
        return $total > 0 ? round($this->total_points / $total, 1) : 0;
    }

    // Get estimated duration per question
    public function getMinutesPerQuestionAttribute()
    {
        $total = $this->total_questions;
        return $total > 0 ? round($this->duration_minutes / $total, 1) : 0;
    }

    // Get active students count
    public function getActiveStudentsCountAttribute()
    {
        return $this->studentSessions()->where('status', 'in_progress')->count();
    }

    // Get finished students count
    public function getFinishedStudentsCountAttribute()
    {
        return $this->studentSessions()->whereIn('status', ['finished', 'timeout'])->count();
    }

    // Get total participants
    public function getTotalParticipantsAttribute()
    {
        return $this->studentSessions()->count();
    }

    // Exam management methods
    public function startExam()
    {
        $this->update([
            'status' => 'active',
            'start_time' => $this->start_time ?? now(),
            'end_time' => $this->end_time ?? now()->addMinutes($this->duration_minutes),
        ]);
    }

    public function finishExam()
    {
        $this->update([
            'status' => 'finished',
            'end_time' => now(),
        ]);
        
        // Finish all active sessions
        $this->studentSessions()
            ->where('status', 'in_progress')
            ->update([
                'status' => 'timeout',
                'finished_at' => now(),
            ]);
    }

    public function setWaiting()
    {
        $this->update(['status' => 'waiting']);
    }

    // Validation methods
    public function hasQuestions()
    {
        return $this->total_questions > 0;
    }

    public function isReadyToStart()
    {
        return $this->hasQuestions() && $this->status !== 'draft';
    }

    // Statistics
    public function getAverageScore()
    {
        return $this->studentSessions()
            ->whereIn('status', ['finished', 'timeout'])
            ->whereNotNull('total_score')
            ->avg('total_score') ?? 0;
    }

    public function getHighestScore()
    {
        return $this->studentSessions()
            ->whereIn('status', ['finished', 'timeout'])
            ->max('total_score') ?? 0;
    }

    public function getLowestScore()
    {
        return $this->studentSessions()
            ->whereIn('status', ['finished', 'timeout'])
            ->where('total_score', '>', 0)
            ->min('total_score') ?? 0;
    }

    // Two-tier specific statistics
    public function getTwoTierStatistics()
    {
        $sessions = $this->studentSessions()
            ->whereIn('status', ['finished', 'timeout'])
            ->get();

        if ($sessions->isEmpty()) {
            return [
                'total_participants' => 0,
                'benar_benar' => ['count' => 0, 'percentage' => 0],
                'benar_salah' => ['count' => 0, 'percentage' => 0], 
                'salah_benar' => ['count' => 0, 'percentage' => 0],
                'salah_salah' => ['count' => 0, 'percentage' => 0],
            ];
        }

        $totalParticipants = $sessions->count();
        $categories = ['benar_benar' => 0, 'benar_salah' => 0, 'salah_benar' => 0, 'salah_salah' => 0];

        // Count answers by category across all sessions
        foreach ($sessions as $session) {
            $answers = $session->studentAnswers;
            foreach ($answers as $answer) {
                if (isset($categories[$answer->category])) {
                    $categories[$answer->category]++;
                }
            }
        }

        $totalAnswers = array_sum($categories);
        $result = ['total_participants' => $totalParticipants];
        
        foreach ($categories as $category => $count) {
            $result[$category] = [
                'count' => $count,
                'percentage' => $totalAnswers > 0 ? round(($count / $totalAnswers) * 100, 1) : 0
            ];
        }

        return $result;
    }

    // Get exam difficulty analysis
    public function getDifficultyAnalysis()
    {
        $questions = $this->getOrderedQuestions();
        $difficultyStats = [];
        
        foreach ($questions as $question) {
            $difficulty = $question->difficulty;
            if (!isset($difficultyStats[$difficulty])) {
                $difficultyStats[$difficulty] = 0;
            }
            $difficultyStats[$difficulty]++;
        }

        return $difficultyStats;
    }
}

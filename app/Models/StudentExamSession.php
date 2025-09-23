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
        'timezone',
        'approved',
        'approved_at',
        'approved_by',
        'extended_time_minutes',
        'joined_at',
        'started_at',
        'page_loaded_at',
        'scheduled_finish_at',
        'finished_at',
        'total_score',
        'scoring_breakdown',
        'status',
        'device_info',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'started_at' => 'datetime',
        'page_loaded_at' => 'datetime',
        'scheduled_finish_at' => 'datetime',
        'finished_at' => 'datetime',
        'approved_at' => 'datetime',
        'scoring_breakdown' => 'array',
        'device_info' => 'array',
        'total_score' => 'integer',
        'extended_time_minutes' => 'integer',
        'approved' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'session_id');
    }
    
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Join exam (enter waiting room)
    public function joinExam($studentName, $studentIdentifier = null, $timezone = 'Asia/Jakarta', $deviceInfo = null)
    {
        $examStatus = $this->exam->status;
        
        $this->update([
            'student_name' => $studentName,
            'student_identifier' => $studentIdentifier,
            'timezone' => $timezone,
            'device_info' => $deviceInfo,
            'joined_at' => now(),
            'status' => $examStatus === 'waiting' ? 'waiting_identity' : 'waiting_approval'
        ]);
    }
    
    // Fill identity (for waiting exam)
    public function fillIdentity($studentName, $studentIdentifier = null)
    {
        // Check current exam status to determine the correct session status
        if ($this->exam->status === 'waiting') {
            // For waiting exams, students are automatically approved after filling identity
            $this->update([
                'student_name' => $studentName,
                'student_identifier' => $studentIdentifier,
                'status' => 'approved',
                'approved' => true,
                'approved_at' => now()
            ]);
        } else if ($this->exam->isActive()) {
            $this->update([
                'student_name' => $studentName,
                'student_identifier' => $studentIdentifier,
                'status' => 'waiting_approval'
            ]);
        } else {
            // For draft or finished exams, keep as waiting_approval
            $this->update([
                'student_name' => $studentName,
                'student_identifier' => $studentIdentifier,
                'status' => 'waiting_approval'
            ]);
        }
    }

    // Start session (approved participants can start)
    public function startSession()
    {
        // For active exams, only approved sessions can start
        if ($this->exam->isActive() && $this->status === 'approved') {
            $this->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }
        
        // For waiting exams, waiting_identity sessions can also start
        if ($this->exam->isWaiting() && $this->status === 'waiting_identity') {
            $this->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }
        
        // For approved sessions in waiting exams, they can also start
        if ($this->exam->isWaiting() && $this->status === 'approved') {
            $this->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }
    }

    // Mark when exam page has fully loaded and set the scheduled finish time
    public function markPageLoaded()
    {
        if ($this->status === 'in_progress' && !$this->scheduled_finish_at) {
            $totalDurationMinutes = $this->exam->duration_minutes + ($this->extended_time_minutes ?? 0);
            $scheduledFinishAt = now()->addMinutes($totalDurationMinutes);
            
            $this->update([
                'page_loaded_at' => now(),
                'scheduled_finish_at' => $scheduledFinishAt,
            ]);
        }
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

    // Extend time for this session
    public function extendTime($minutes)
    {
        $currentExtended = $this->extended_time_minutes ?? 0;
        $updateData = [
            'extended_time_minutes' => $currentExtended + $minutes
        ];
        
        // Update scheduled finish time if it exists
        if ($this->scheduled_finish_at) {
            $updateData['scheduled_finish_at'] = $this->scheduled_finish_at->addMinutes($minutes);
        }
        
        $this->update($updateData);
    }

    // Check if session is expired
    public function isExpired()
    {
        if (!$this->scheduled_finish_at || in_array($this->status, ['finished', 'timeout'])) {
            return false;
        }

        return now()->gt($this->scheduled_finish_at);
    }

    // Get remaining time in seconds
    public function getRemainingTimeAttribute()
    {
        if (!$this->scheduled_finish_at || in_array($this->status, ['finished', 'timeout'])) {
            return 0;
        }

        $now = now();
        if ($now->gte($this->scheduled_finish_at)) {
            return 0; // Waktu sudah habis
        }

        return $now->diffInSeconds($this->scheduled_finish_at);
    }

    // Get total duration including extended time
    public function getTotalDurationMinutes()
    {
        if (!$this->exam) {
            return 0;
        }
        
        return $this->exam->duration_minutes + ($this->extended_time_minutes ?? 0);
    }

    // Get extended time formatted
    public function getExtendedTimeFormatted()
    {
        $extendedTime = $this->extended_time_minutes ?? 0;
        if ($extendedTime === 0) {
            return 'Tidak ada perpanjangan';
        }
        
        return "+{$extendedTime} menit";
    }
    
    // Get status display
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'waiting_identity' => 'Menunggu mengisi identitas',
            'waiting_approval' => 'Menunggu persetujuan guru',
            'approved' => 'Disetujui - Siap mulai',
            'in_progress' => 'Sedang mengerjakan',
            'finished' => 'Selesai',
            'timeout' => 'Waktu habis',
            'kicked' => 'Dikeluarkan',
            default => $this->status
        };
    }
    
    // Check if can start exam
    public function canStartExam()
    {
        // For active exams, only approved sessions can start
        if ($this->exam->isActive()) {
            return $this->status === 'approved';
        }
        
        // For waiting exams, waiting_identity sessions can also start
        if ($this->exam->isWaiting()) {
            return in_array($this->status, ['approved', 'waiting_identity']);
        }
        
        return false;
    }
    
    // Check if can continue exam
    public function canContinueExam()
    {
        // For active exams
        if ($this->exam->isActive()) {
            return $this->status === 'in_progress' && !$this->isExpired();
        }
        
        // For waiting exams, in_progress sessions can continue
        if ($this->exam->isWaiting()) {
            return $this->status === 'in_progress' && !$this->isExpired();
        }
        
        return false;
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
    
    // Static methods for finding sessions
    public static function findByExamCode($code)
    {
        return self::whereHas('exam', function($query) use ($code) {
            $query->where('current_code', $code)
                  ->orWhere('code', $code);
        })->get();
    }
    
    public static function findActiveSession($examId, $studentName, $studentIdentifier = null)
    {
        $query = self::where('exam_id', $examId)
                    ->where('student_name', $studentName)
                    ->whereNotIn('status', ['finished', 'timeout', 'kicked']);
                    
        if ($studentIdentifier) {
            $query->where('student_identifier', $studentIdentifier);
        }
        
        return $query->first();
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
    
    public function scopeWaiting($query)
    {
        return $query->whereIn('status', ['waiting_identity', 'waiting_approval']);
    }
    
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    public function scopeKicked($query)
    {
        return $query->where('status', 'kicked');
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    // Helper methods for teacher dashboard
    public function getJoinTimeFormatted()
    {
        if (!$this->joined_at) return '-';
        
        return $this->joined_at->format('H:i:s');
    }
    
    public function getStartTimeFormatted()
    {
        if (!$this->started_at) return '-';
        
        return $this->started_at->format('H:i:s');
    }
    
    public function getFinishTimeFormatted()
    {
        if (!$this->finished_at) return '-';
        
        return $this->finished_at->format('H:i:s');
    }
    
    public function getDurationFormatted()
    {
        if (!$this->started_at || !$this->finished_at) return '-';
        
        $minutes = $this->started_at->diffInMinutes($this->finished_at);
        return "{$minutes} menit";
    }
}

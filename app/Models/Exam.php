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
        'current_code',
        'code_generated_at',
        'subject_id',
        'grade',
        'semester',
        'duration_minutes',
        'status',
        'shuffle_questions',
        'show_result_immediately',
        'created_by',
    ];

    protected $casts = [
        'code_generated_at' => 'datetime',
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

    // Generate kode ujian unik 6 karakter (hanya huruf)
    private function generateUniqueCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    // Code generation methods (hanya huruf)
    public function generateCurrentCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6));
        } while (self::where('current_code', $code)->exists());

        $this->update([
            'current_code' => $code,
            'code_generated_at' => now()
        ]);

        return $code;
    }

    public function regenerateCurrentCode()
    {
        if ($this->status !== 'waiting') {
            return [
                'success' => false,
                'message' => 'Kode hanya bisa di-generate ulang saat ujian berstatus waiting'
            ];
        }

        $newCode = $this->generateCurrentCode();

        return [
            'success' => true,
            'message' => 'Kode berhasil di-generate ulang',
            'code' => $newCode
        ];
    }

    public function getCurrentCode()
    {
        return $this->current_code ?? $this->code;
    }

    // Status checks
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isWaiting()
    {
        return $this->status === 'waiting';
    }

    public function canStart()
    {
        return $this->status === 'waiting' && $this->hasQuestions();
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
        $this->update(['status' => 'active']);
        
        // Update all approved sessions to ready state
        $this->studentSessions()
            ->where('status', 'approved')
            ->update(['status' => 'approved']);
            
        // Update waiting_identity sessions to approved for exams that were draft
        $this->studentSessions()
            ->where('status', 'waiting_identity')
            ->update(['status' => 'approved']);
    }

    public function finishExam()
    {
        $this->update(['status' => 'finished']);
        
        // Finish all active sessions
        $this->studentSessions()
            ->where('status', 'in_progress')
            ->each(function ($session) {
                $session->finishSession();
            });
            
        // Approve all waiting sessions so they can start (they'll finish immediately due to exam status)
        $this->studentSessions()
            ->whereIn('status', ['waiting_identity', 'waiting_approval'])
            ->update(['status' => 'approved']);
    }

    public function setWaiting()
    {
        $this->update(['status' => 'waiting']);
        $this->generateCurrentCode();
        
        // Update waiting_approval sessions to waiting_identity for exams that were draft
        $this->studentSessions()
            ->where('status', 'waiting_approval')
            ->update(['status' => 'waiting_identity']);
    }
    
    public function setDraft()
    {
        $this->update([
            'status' => 'draft',
            'current_code' => null,
            'code_generated_at' => null
        ]);
        
        // Reset all active sessions when exam goes back to draft
        $this->studentSessions()
            ->whereIn('status', ['approved', 'in_progress'])
            ->update(['status' => 'waiting_approval']);
    }

    public function extendTime($minutes, $sessionId = null)
    {
        try {
            if ($sessionId) {
                // Extend time for specific session
                $session = $this->studentSessions()->find($sessionId);
                if (!$session) {
                    return [
                        'success' => false,
                        'message' => 'Sesi tidak ditemukan'
                    ];
                }
                
                $session->extendTime($minutes);
                
                return [
                    'success' => true,
                    'message' => "Waktu diperpanjang {$minutes} menit untuk {$session->student_name}"
                ];
            } else {
                // Extend time for all active sessions
                $activeSessions = $this->studentSessions()
                    ->whereIn('status', ['approved', 'in_progress'])
                    ->get();

                foreach ($activeSessions as $session) {
                    $session->extendTime($minutes);
                }

                return [
                    'success' => true,
                    'extended_sessions' => $activeSessions->count(),
                    'message' => "Waktu diperpanjang {$minutes} menit untuk {$activeSessions->count()} peserta"
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperpanjang waktu: ' . $e->getMessage()
            ];
        }
    }

    public function kickParticipant($participantId)
    {
        try {
            $session = $this->studentSessions()->find($participantId);

            if (!$session) {
                return [
                    'success' => false,
                    'message' => 'Peserta tidak ditemukan'
                ];
            }

            $session->update([
                'status' => 'kicked',
                'finished_at' => now()
            ]);

            return [
                'success' => true,
                'message' => "Peserta {$session->student_name} berhasil dikeluarkan dari ujian"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengeluarkan peserta: ' . $e->getMessage()
            ];
        }
    }
    
    public function approveParticipant($participantId)
    {
        try {
            $session = $this->studentSessions()->find($participantId);

            if (!$session) {
                return [
                    'success' => false,
                    'message' => 'Peserta tidak ditemukan'
                ];
            }
            
            if ($session->status !== 'waiting_approval') {
                return [
                    'success' => false,
                    'message' => 'Peserta tidak dalam status menunggu persetujuan'
                ];
            }

            $session->update([
                'status' => 'approved',
                'approved' => true,
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ]);

            return [
                'success' => true,
                'message' => "Peserta {$session->student_name} berhasil disetujui"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui peserta: ' . $e->getMessage()
            ];
        }
    }

    public function forceFinishAllSessions()
    {
        try {
            $activeSessions = $this->studentSessions()
                ->where('status', 'in_progress')
                ->get();
                
            foreach ($activeSessions as $session) {
                $session->finishSession();
            }

            return [
                'success' => true,
                'finished_sessions' => $activeSessions->count(),
                'message' => "Berhasil mengakhiri {$activeSessions->count()} sesi aktif"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengakhiri sesi: ' . $e->getMessage()
            ];
        }
    }

    public function getActiveParticipantsData()
    {
        return $this->studentSessions()
            ->where('status', 'in_progress')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'name' => $session->student_name,
                    'email' => $session->student_identifier ?? 'N/A',
                    'status' => $session->status,
                    'started_at' => $session->started_at,
                    'progress' => $this->calculateSessionProgress($session),
                    'current_score' => $session->total_score ?? 0,
                    'extended_time' => $session->extended_time ?? 0,
                ];
            });
    }

    private function calculateSessionProgress($session)
    {
        if ($session->status === 'registered') {
            return 0;
        }

        $totalQuestions = $this->examQuestions()->count();
        $answeredQuestions = $session->studentAnswers()->count();

        return $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100) : 0;
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

    // Get participants by status
    public function getWaitingParticipants()
    {
        return $this->studentSessions()
            ->whereIn('status', ['waiting_identity', 'waiting_approval'])
            ->latest('joined_at')
            ->get();
    }
    
    public function getActiveParticipants()
    {
        return $this->studentSessions()
            ->whereIn('status', ['approved', 'in_progress'])
            ->latest('started_at')
            ->get();
    }
    
    public function getFinishedParticipants()
    {
        return $this->studentSessions()
            ->whereIn('status', ['finished', 'timeout'])
            ->latest('finished_at')
            ->get();
    }
}

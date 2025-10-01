<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\ExamQuestion;
use App\Models\StudentExamSession;
use App\Models\ExamResult;
use App\Models\StudentAnswer;
use App\Traits\HasTimezone;

class GuruController extends Controller
{
    use HasTimezone;
    
    public function dashboard()
    {
        $stats = [
            'total_exams' => Exam::where('created_by', auth()->id())->count(),
            'active_exams' => Exam::where('created_by', auth()->id())->where('status', 'active')->count(),
            'waiting_exams' => Exam::where('created_by', auth()->id())->where('status', 'waiting')->count(),
            'total_participants' => StudentExamSession::whereHas('exam', function($q) {
                $q->where('created_by', auth()->id());
            })->count(),
            'recent_exams' => Exam::where('created_by', auth()->id())
                ->with('subject')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('guru.dashboard', compact('stats'));
    }

    // ===== EXAM MANAGEMENT =====
    public function exams()
    {
        $exams = Exam::where('created_by', auth()->id())
            ->with(['subject'])
            ->withCount('examQuestions')
            ->latest()
            ->paginate(15);

        $subjects = Subject::where('is_active', true)->get();

        return view('guru.exams.index', compact('exams', 'subjects'));
    }

    public function createExam()
    {
        $subjects = Subject::where('is_active', true)->get();
        return view('guru.exams.create', compact('subjects'));
    }

    public function storeExam(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|string|max:10',
            'semester' => 'required|in:gasal,genap',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'shuffle_questions' => 'boolean',
            'show_result_immediately' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*' => 'exists:questions,id',
        ]);

        // Generate unique exam code
        do {
            $code = strtoupper(Str::random(6));
        } while (Exam::where('code', $code)->exists());

        $exam = Exam::create([
            'title' => $request->title,
            'description' => $request->description,
            'code' => $code,
            'subject_id' => $request->subject_id,
            'grade' => $request->grade,
            'semester' => $request->semester,
            'duration_minutes' => $request->duration_minutes,
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'show_result_immediately' => $request->boolean('show_result_immediately'),
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        // Add questions to exam
        foreach ($request->questions as $index => $questionId) {
            ExamQuestion::create([
                'exam_id' => $exam->id,
                'question_id' => $questionId,
                'question_order' => $index + 1,
                'points' => 10, // Default points
            ]);
        }

        return redirect()->route('guru.exams.index')
            ->with('success', 'Ujian berhasil dibuat dengan kode: ' . $code);
    }

    public function showExam(Exam $exam)
    {
        $this->authorize('view', $exam);

        $exam->load(['subject', 'examQuestions.question']);
        $questions_count = $exam->examQuestions->count();
        $participants_count = StudentExamSession::where('exam_id', $exam->id)->count();

        return response()->json([
            'id' => $exam->id,
            'title' => $exam->title,
            'description' => $exam->description,
            'code' => $exam->code,
            'current_code' => $exam->getCurrentCode(),
            'subject' => $exam->subject,
            'duration' => $exam->duration_minutes,
            'status' => $exam->status,
            'questions_count' => $questions_count,
            'participants_count' => $participants_count,
            'shuffle_questions' => $exam->shuffle_questions,
            'show_result_immediately' => $exam->show_result_immediately,
            'created_at' => $exam->created_at,
        ]);
    }

    public function editExam(Exam $exam)
    {
        $this->authorize('update', $exam);
        
        $subjects = Subject::where('is_active', true)->get();
        $selectedQuestions = $exam->examQuestions()->with('question')->get();
        
        return view('guru.exams.edit', compact('exam', 'subjects', 'selectedQuestions'));
    }

    public function updateExam(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|string|max:10',
            'semester' => 'required|in:gasal,genap',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'shuffle_questions' => 'boolean',
            'show_result_immediately' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*' => 'exists:questions,id',
        ]);

        $exam->update([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'grade' => $request->grade,
            'semester' => $request->semester,
            'duration_minutes' => $request->duration_minutes,
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'show_result_immediately' => $request->boolean('show_result_immediately'),
        ]);

        // Update exam questions
        $exam->examQuestions()->delete();
        foreach ($request->questions as $index => $questionId) {
            ExamQuestion::create([
                'exam_id' => $exam->id,
                'question_id' => $questionId,
                'question_order' => $index + 1,
                'points' => 10,
            ]);
        }

        return redirect()->route('guru.exams.index')
            ->with('success', 'Ujian berhasil diperbarui.');
    }

    public function deleteExam(Exam $exam)
    {
        $this->authorize('delete', $exam);
        
        // Only allow deletion for draft and finished exams
        if (!in_array($exam->status, ['draft', 'finished'])) {
            return redirect()->route('guru.exams.index')
                ->with('error', 'Hanya ujian dengan status draft atau selesai yang dapat dihapus.');
        }
        
        DB::transaction(function () use ($exam) {
            // Delete related exam sessions and their data
            $sessions = StudentExamSession::where('exam_id', $exam->id)->get();
            
            foreach ($sessions as $session) {
                // Delete student answers
                StudentAnswer::where('session_id', $session->id)->delete();
                
                // Delete the session
                $session->delete();
            }
            
            // Delete exam questions
            ExamQuestion::where('exam_id', $exam->id)->delete();
            
            // Delete exam results if exists
            ExamResult::where('exam_id', $exam->id)->delete();
            
            // Finally delete the exam
            $exam->delete();
        });

        $message = $exam->status === 'finished' 
            ? 'Ujian dan semua hasil ujian berhasil dihapus.' 
            : 'Ujian berhasil dihapus.';
            
        return redirect()->route('guru.exams.index')
            ->with('success', $message);
    }

    public function duplicateExam(Exam $exam)
    {
        $this->authorize('view', $exam);

        // Generate new unique code
        do {
            $code = strtoupper(Str::random(6));
        } while (Exam::where('code', $code)->exists());

        // Create duplicate exam
        $newExam = $exam->replicate();
        $newExam->code = $code;
        $newExam->current_code = null;
        $newExam->code_generated_at = null;
        $newExam->title = $exam->title . ' (Copy)';
        $newExam->status = 'draft';
        $newExam->created_by = auth()->id();
        $newExam->save();

        // Duplicate exam questions
        foreach ($exam->examQuestions as $examQuestion) {
            $newExamQuestion = $examQuestion->replicate();
            $newExamQuestion->exam_id = $newExam->id;
            $newExamQuestion->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Ujian berhasil digandakan dengan kode: ' . $code,
            'exam' => $newExam
        ]);
    }

    // ===== EXAM STATUS MANAGEMENT =====
    public function updateExamStatus(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        $request->validate([
            'status' => 'required|in:draft,waiting,active,finished'
        ]);

        $newStatus = $request->status;

        switch ($newStatus) {
            case 'waiting':
                $exam->setWaiting();
                $message = 'Ujian dibuka untuk peserta. Kode ujian: ' . $exam->getCurrentCode();
                break;
            case 'active':
                $exam->startExam();
                $message = 'Ujian dimulai!';
                break;
            case 'finished':
                $exam->finishExam();
                $message = 'Ujian selesai!';
                break;
            case 'draft':
                $exam->setDraft();
                $message = 'Ujian dikembalikan ke status draft';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'status' => $exam->status,
            'current_code' => $exam->getCurrentCode()
        ]);
    }

    // ===== WAITING ROOM & MONITORING =====
    public function waitingRoom(Exam $exam)
    {
        $this->authorize('view', $exam);

        $participants = $this->getParticipantsData($exam);
        $stats = $this->getExamStats($exam);

        return view('guru.exams.waiting-room', compact('exam', 'participants', 'stats'));
    }

    public function getParticipants(Exam $exam)
    {
        $this->authorize('view', $exam);

        $participants = $this->getParticipantsData($exam);
        $stats = $this->getExamStats($exam);

        return response()->json([
            'participants' => $participants,
            'statistics' => $stats,
            'current_code' => $exam->getCurrentCode(),
            'code_generated_at' => $exam->code_generated_at
        ]);
    }

    private function getParticipantsData(Exam $exam)
    {
        return StudentExamSession::where('exam_id', $exam->id)
            ->latest('joined_at')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'name' => $session->student_name,
                    'identifier' => $session->student_identifier ?? 'N/A',
                    'status' => $session->status,
                    'status_display' => $session->status_display,
                    'joined_at' => $session->getJoinTimeFormatted(),
                    'started_at' => $session->getStartTimeFormatted(),
                    'finished_at' => $session->getFinishTimeFormatted(),
                    'duration' => $session->getDurationFormatted(),
                    'progress' => $this->calculateProgress($session),
                    'current_score' => $session->total_score ?? 0,
                    'extended_time' => $session->extended_time_minutes ?? 0,
                    'timezone' => $session->timezone,
                    'approved' => $session->approved,
                    'approved_at' => $session->approved_at?->format('H:i:s'),
                ];
            });
    }

    private function getExamStats(Exam $exam)
    {
        $sessions = StudentExamSession::where('exam_id', $exam->id)->get();

        return [
            'total' => $sessions->count(),
            'waiting_identity' => $sessions->where('status', 'waiting_identity')->count(),
            'waiting_approval' => $sessions->where('status', 'waiting_approval')->count(),
            'approved' => $sessions->where('status', 'approved')->count(),
            'in_progress' => $sessions->where('status', 'in_progress')->count(),
            'finished' => $sessions->whereIn('status', ['finished', 'timeout'])->count(),
            'kicked' => $sessions->where('status', 'kicked')->count(),
            'average_score' => $sessions->whereIn('status', ['finished', 'timeout'])
                                     ->whereNotNull('total_score')
                                     ->avg('total_score') ?? 0
        ];
    }

    private function calculateProgress($session)
    {
        if (!in_array($session->status, ['in_progress', 'finished', 'timeout'])) {
            return 0;
        }

        $totalQuestions = ExamQuestion::where('exam_id', $session->exam_id)->count();
        $answeredQuestions = StudentAnswer::where('session_id', $session->id)
            ->whereNotNull('tier1_answer')
            ->whereNotNull('tier2_answer')
            ->count();

        return $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100) : 0;
    }

    // ===== PARTICIPANT MANAGEMENT =====
    public function approveParticipant(Request $request, Exam $exam, $participantId)
    {
        $this->authorize('update', $exam);

        $result = $exam->approveParticipant($participantId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function kickParticipant(Request $request, Exam $exam, $participantId)
    {
        $this->authorize('update', $exam);

        $result = $exam->kickParticipant($participantId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function extendTime(Request $request, Exam $exam, $participantId = null)
    {
        $this->authorize('update', $exam);

        $request->validate([
            'minutes' => 'required|integer|min:1|max:60'
        ]);

        $result = $exam->extendTime($request->minutes, $participantId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function broadcastMessage(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        $request->validate([
            'type' => 'required|in:info,warning,success,error',
            'content' => 'required|string|max:500'
        ]);

        // In a real application, you would broadcast this via WebSocket/Pusher
        // For now, we'll just return success
        
        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim ke semua peserta'
        ]);
    }

    public function endExam(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);

        $exam->finishExam();

        return response()->json([
            'success' => true,
            'message' => 'Ujian berhasil diakhiri'
        ]);
    }

    // ===== EXPORT & RESULTS =====
    public function exportCurrentResults(Exam $exam)
    {
        $this->authorize('view', $exam);

        $sessions = StudentExamSession::where('exam_id', $exam->id)
            ->with(['studentAnswers'])
            ->get();

        $filename = 'hasil_sementara_' . $exam->code . '_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sessions) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Nama Siswa',
                'Identitas',
                'Status',
                'Waktu Masuk',
                'Waktu Mulai',
                'Waktu Selesai',
                'Durasi',
                'Progress (%)',
                'Skor Sementara',
                'Waktu Tambahan'
            ]);

            foreach ($sessions as $session) {
                $progress = $this->calculateProgress($session);
                
                fputcsv($file, [
                    $session->student_name,
                    $session->student_identifier ?? '-',
                    $session->status_display,
                    $session->getJoinTimeFormatted(),
                    $session->getStartTimeFormatted(),
                    $session->getFinishTimeFormatted(),
                    $session->getDurationFormatted(),
                    $progress . '%',
                    $session->total_score ?? 0,
                    $session->getExtendedTimeFormatted()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function examResults(Exam $exam)
    {
        $this->authorize('view', $exam);

        // Get finished sessions instead of ExamResult
        $sessions = StudentExamSession::where('exam_id', $exam->id)
            ->whereIn('status', ['finished', 'timeout'])
            ->with(['studentAnswers'])
            ->latest('finished_at')
            ->get();

        // Calculate stats
        $totalParticipants = StudentExamSession::where('exam_id', $exam->id)->count();
        $completedSessions = $sessions->count();
        $averageScore = $sessions->whereNotNull('total_score')->avg('total_score') ?? 0;
        $highestScore = $sessions->whereNotNull('total_score')->max('total_score') ?? 0;
        $lowestScore = $sessions->whereNotNull('total_score')->min('total_score') ?? 0;
        
        // Calculate pass rate (assuming passing score is 60% of total points)
        $passingScore = $exam->total_points * 0.6;
        $passedCount = $sessions->where('total_score', '>=', $passingScore)->count();
        $failedCount = $completedSessions - $passedCount;
        $passRate = $completedSessions > 0 ? ($passedCount / $completedSessions) * 100 : 0;

        // Score distribution for chart
        $scoresDistribution = [0, 0, 0, 0, 0]; // 0-20%, 21-40%, 41-60%, 61-80%, 81-100%
        foreach ($sessions as $session) {
            if ($session->total_score !== null && $exam->total_points > 0) {
                $percentage = ($session->total_score / $exam->total_points) * 100;
                if ($percentage <= 20) $scoresDistribution[0]++;
                elseif ($percentage <= 40) $scoresDistribution[1]++;
                elseif ($percentage <= 60) $scoresDistribution[2]++;
                elseif ($percentage <= 80) $scoresDistribution[3]++;
                else $scoresDistribution[4]++;
            }
        }

        // Question analysis
        $questionAnalysis = $this->getQuestionAnalysis($exam);

        // Transform sessions to results format for view compatibility
        $results = $sessions->map(function ($session) use ($exam) {
            $percentage = $exam->total_points > 0 ? ($session->total_score / $exam->total_points) * 100 : 0;
            return (object) [
                'id' => $session->id,
                'user' => (object) [
                    'name' => $session->student_name,
                    'email' => $session->student_identifier ?? 'N/A'
                ],
                'started_at' => $session->started_at,
                'completed_at' => $session->finished_at,
                'score' => $session->total_score ?? 0,
                'total_score' => $exam->total_points,
                'percentage' => round($percentage, 1),
                'status' => $session->status === 'finished' ? 'completed' : $session->status
            ];
        });

        return view('guru.exams.results', compact(
            'exam', 
            'results', 
            'totalParticipants',
            'averageScore',
            'highestScore',
            'passRate',
            'passedCount',
            'failedCount',
            'scoresDistribution',
            'questionAnalysis'
        ));
    }

    private function getQuestionAnalysis(Exam $exam)
    {
        $questions = $exam->examQuestions()->with('question')->get();
        $analysis = [];

        foreach ($questions as $examQuestion) {
            $question = $examQuestion->question;
            
            // Get all answers for this question
            $answers = StudentAnswer::where('question_id', $question->id)
                ->whereHas('session', function($q) use ($exam) {
                    $q->where('exam_id', $exam->id);
                })
                ->get();

            $totalAnswers = $answers->count();
            $correctAnswers = $answers->where('tier1_answer', $question->tier1_correct_answer)
                                   ->where('tier2_answer', $question->tier2_correct_answer)
                                   ->count();
            $wrongAnswers = $totalAnswers - $correctAnswers;
            $successRate = $totalAnswers > 0 ? ($correctAnswers / $totalAnswers) * 100 : 0;

            $analysis[] = [
                'question' => $question,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'success_rate' => round($successRate, 1)
            ];
        }

        return $analysis;
    }

    public function exportResults(Exam $exam)
    {
        $this->authorize('view', $exam);

        $sessions = StudentExamSession::where('exam_id', $exam->id)
            ->whereIn('status', ['finished', 'timeout'])
            ->with(['studentAnswers'])
            ->get();

        $filename = 'hasil_final_' . $exam->code . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($sessions) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Nama Siswa',
                'Identitas',
                'Status',
                'Waktu Mulai',
                'Waktu Selesai',
                'Durasi',
                'Benar-Benar',
                'Benar-Salah',
                'Salah-Benar',
                'Salah-Salah',
                'Total Skor',
                'Persentase'
            ]);

            foreach ($sessions as $session) {
                $breakdown = $session->breakdown_summary;
                $maxScore = $session->exam->total_points;
                $percentage = $maxScore > 0 ? round(($session->total_score / $maxScore) * 100, 1) : 0;
                
                fputcsv($file, [
                    $session->student_name,
                    $session->student_identifier ?? '-',
                    $session->status_display,
                    $session->getStartTimeFormatted(),
                    $session->getFinishTimeFormatted(),
                    $session->getDurationFormatted(),
                    $breakdown['benar_benar'],
                    $breakdown['benar_salah'],
                    $breakdown['salah_benar'],
                    $breakdown['salah_salah'],
                    $session->total_score ?? 0,
                    $percentage . '%'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ===== QUESTION BANK =====
    public function questionBank(Request $request)
    {
        $query = Question::with(['chapter.subject', 'creator'])
            ->where('is_active', true);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tier1_question', 'like', "%{$search}%")
                  ->orWhere('tier2_question', 'like', "%{$search}%");
            });
        }

        // Subject filter
        if ($request->filled('subject_id')) {
            $query->whereHas('chapter', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        // Chapter filter
        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }

        $questions = $query->latest()->paginate(20)->withQueryString();

        $subjects = Subject::where('is_active', true)->get();
        $chapters = Chapter::where('is_active', true)->get();

        return view('guru.questions.index', compact('questions', 'subjects', 'chapters'));
    }

    public function filterQuestions(Request $request)
    {
        $query = Question::with(['chapter.subject', 'creator'])
            ->where('is_active', true);

        if ($request->filled('subject_id')) {
            $query->whereHas('chapter', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }

        if ($request->filled('grade')) {
            $query->whereHas('chapter', function($q) use ($request) {
                $q->where('grade', $request->grade);
            });
        }

        if ($request->filled('semester')) {
            $query->whereHas('chapter', function($q) use ($request) {
                $q->where('semester', $request->semester);
            });
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $questions = $query->latest()->paginate(20);
        $subjects = Subject::where('is_active', true)->get();
        $chapters = Chapter::where('is_active', true)->get();

        return view('guru.questions.index', compact('questions', 'subjects', 'chapters'));
    }

    public function searchQuestions(Request $request)
    {
        $query = Question::with(['chapter.subject'])
            ->where('is_active', true);

        // Filter by specific IDs (for edit page)
        if ($request->filled('ids')) {
            $ids = explode(',', $request->ids);
            $query->whereIn('id', $ids);
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('chapter', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }

        if ($request->filled('grade')) {
            $query->whereHas('chapter', function($q) use ($request) {
                $q->where('grade', $request->grade);
            });
        }

        if ($request->filled('semester')) {
            $query->whereHas('chapter', function($q) use ($request) {
                $q->where('semester', $request->semester);
            });
        }

        $questions = $query->latest()->get();

        return response()->json($questions);
    }

    public function getSubjectChapters($subjectId)
    {
        $chapters = Chapter::where('subject_id', $subjectId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'name', 'order']);

        return response()->json($chapters);
    }

    public function showQuestion(Question $question)
    {
        $question->load(['chapter.subject', 'creator']);

        return response()->json([
            'id' => $question->id,
            'tier1_question' => $question->tier1_question,
            'tier1_options' => $question->tier1_options,
            'tier1_correct_answer' => $question->tier1_correct_answer,
            'tier2_question' => $question->tier2_question,
            'tier2_options' => $question->tier2_options,
            'tier2_correct_answer' => $question->tier2_correct_answer,
            'difficulty' => $question->difficulty,
            'points' => $question->points ?? 10,
            'status' => $question->is_active ? 'active' : 'inactive',
            'subject' => $question->chapter->subject ?? null,
            'chapter' => $question->chapter ?? null,
            'created_at' => $question->created_at,
        ]);
    }

    public function showQuestionStats(Question $question)
    {
        // Get basic usage stats
        $totalUsage = ExamQuestion::where('question_id', $question->id)->count();

        // Get exams using this question
        $exams = Exam::whereHas('examQuestions', function($q) use ($question) {
            $q->where('question_id', $question->id);
        })->get(['id', 'title', 'created_at']);

        // Get answer statistics
        $totalAnswers = StudentAnswer::where('question_id', $question->id)->count();

        $correctBoth = StudentAnswer::where('question_id', $question->id)
            ->where('result_category', 'benar-benar')
            ->count();

        $tier1Correct = StudentAnswer::where('question_id', $question->id)
            ->whereIn('result_category', ['benar-benar', 'benar-salah'])
            ->count();

        $tier2Correct = StudentAnswer::where('question_id', $question->id)
            ->whereIn('result_category', ['benar-benar', 'salah-benar'])
            ->count();

        return response()->json([
            'total_views' => 0, // Not tracked
            'total_attempts' => $totalAnswers,
            'correct_answers' => $correctBoth,
            'success_rate' => $totalAnswers > 0 ? round(($correctBoth / $totalAnswers) * 100, 1) : 0,
            'tier1_success_rate' => $totalAnswers > 0 ? round(($tier1Correct / $totalAnswers) * 100, 1) : 0,
            'tier2_success_rate' => $totalAnswers > 0 ? round(($tier2Correct / $totalAnswers) * 100, 1) : 0,
            'exams' => $exams,
        ]);
    }
}
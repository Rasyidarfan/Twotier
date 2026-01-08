<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\StudentExamSession;
use App\Models\StudentAnswer;
use App\Models\Question;

class StudentController extends Controller
{
    // ===== JOIN EXAM =====
    public function showJoinForm()
    {
        return view('student.join');
    }

    public function joinExam(Request $request)
    {
        $request->validate([
            'exam_code' => 'required|string|size:6',
        ]);

        $examCode = strtoupper($request->exam_code);
        
        // Find exam by current code or regular code
        $exam = Exam::where('current_code', $examCode)
                    ->orWhere('code', $examCode)
                    ->first();

        if (!$exam) {
            return back()->withErrors(['exam_code' => 'Kode ujian tidak valid atau ujian tidak ditemukan.']);
        }

        if (!in_array($exam->status, ['waiting', 'active'])) {
            return back()->withErrors(['exam_code' => 'Ujian tidak tersedia saat ini.']);
        }

        // Check if exam has questions
        if (!$exam->hasQuestions()) {
            return back()->withErrors(['exam_code' => 'Ujian belum memiliki soal.']);
        }

        // Get device timezone from JavaScript (will be handled in frontend)
        $deviceTimezone = $request->input('timezone', 'Asia/Jakarta');
        $deviceInfo = [
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'platform' => $request->input('platform'),
            'browser' => $request->input('browser')
        ];

        // Create session - always start with waiting_identity
        // Students must fill identity first, then wait for teacher approval
        $session = StudentExamSession::create([
            'exam_id' => $exam->id,
            'timezone' => $deviceTimezone,
            'device_info' => $deviceInfo,
            'joined_at' => now(),
            'status' => 'waiting_identity'
        ]);

        return redirect()->route('exam.waiting-room', $session->id);
    }

    // ===== WAITING ROOM =====
    public function waitingRoom(StudentExamSession $session)
    {
        $exam = $session->exam;
        
        // Check if exam is still available
        if (!in_array($exam->status, ['waiting', 'active'])) {
            return view('student.exam-unavailable', ['message' => 'Ujian sudah selesai atau tidak tersedia.']);
        }

        // Handle different session states
        switch ($session->status) {
            case 'waiting_identity':
                return $this->showIdentityForm($session);
                
            case 'waiting_approval':
                return $this->showWaitingApproval($session);
                
            case 'approved':
                return $this->showReadyToStart($session);
                
            case 'in_progress':
                return redirect()->route('exam.take', $session->id);
                
            case 'finished':
            case 'timeout':
                return redirect()->route('exam.result', $session->id);
                
            case 'kicked':
                return view('student.exam-unavailable', ['message' => 'Anda telah dikeluarkan dari ujian oleh guru.']);
                
            default:
                return view('student.exam-unavailable', ['message' => 'Status sesi tidak valid.']);
        }
    }

    private function showIdentityForm(StudentExamSession $session)
    {
        $exam = $session->exam;
        $otherParticipants = StudentExamSession::where('exam_id', $exam->id)
            ->where('id', '!=', $session->id)
            ->whereNotIn('status', ['kicked'])
            ->whereNotNull('student_name')
            ->latest('joined_at')
            ->get();

        return view('student.waiting-room.identity-form', compact('session', 'exam', 'otherParticipants'));
    }

    private function showWaitingApproval(StudentExamSession $session)
    {
        $exam = $session->exam;
        $activeParticipants = StudentExamSession::where('exam_id', $exam->id)
            ->whereIn('status', ['approved', 'in_progress', 'finished'])
            ->whereNotNull('student_name')
            ->latest('started_at')
            ->get();

        return view('student.waiting-room.waiting-approval', compact('session', 'exam', 'activeParticipants'));
    }

    private function showReadyToStart(StudentExamSession $session)
    {
        $exam = $session->exam;
        
        return view('student.waiting-room.ready-to-start', compact('session', 'exam'));
    }

    public function submitIdentity(Request $request, StudentExamSession $session)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_identifier' => 'nullable|string|max:255',
        ]);

        $exam = $session->exam;

        // Check for duplicate name in same exam
        $duplicate = StudentExamSession::where('exam_id', $exam->id)
            ->where('id', '!=', $session->id)
            ->where('student_name', $request->student_name)
            ->whereNotIn('status', ['kicked'])
            ->first();

        if ($duplicate) {
            return back()->withErrors(['student_name' => 'Nama sudah digunakan oleh peserta lain.']);
        }

        $session->fillIdentity($request->student_name, $request->student_identifier);

        return redirect()->route('exam.waiting-room', $session->id);
    }

    public function selectExistingParticipant(Request $request, StudentExamSession $session)
    {
        $request->validate([
            'existing_session_id' => 'required|exists:student_exam_sessions,id'
        ]);

        $existingSession = StudentExamSession::find($request->existing_session_id);

        if (!$existingSession || $existingSession->exam_id !== $session->exam_id) {
            return back()->withErrors(['existing_session_id' => 'Sesi tidak valid.']);
        }

        // Check if session can potentially be continued (not finished/timeout/kicked)
        if (in_array($existingSession->status, ['finished', 'timeout', 'kicked'])) {
            return back()->withErrors(['existing_session_id' => 'Sesi tidak dapat dilanjutkan.']);
        }

        // For active exams, require teacher re-approval for security
        // Change existing session status to waiting_approval
        if ($session->exam->status === 'active' && $existingSession->status === 'in_progress') {
            $existingSession->update([
                'status' => 'waiting_approval',
                'approved' => false,
                'approved_at' => null,
                'approved_by' => null
            ]);
        }

        // Remove current session (the duplicate one)
        $session->delete();

        // Redirect to waiting room of existing session
        return redirect()->route('exam.waiting-room', $existingSession->id);
    }

    // ===== TAKE EXAM =====
    public function startExam(StudentExamSession $session)
    {
        if (!$session->canStartExam()) {
            return redirect()->route('exam.waiting-room', $session->id);
        }

        // Verify exam is active before allowing start
        if ($session->exam->status !== 'active') {
            return redirect()->back()->with('error', 'Ujian belum dimulai oleh guru. Status ujian: ' . $session->exam->status_display);
        }

        $session->startSession();

        return redirect()->route('exam.take', $session->id);
    }

    public function takeExam(StudentExamSession $session)
    {
        $exam = $session->exam;

        // Auto-start session if approved and exam is active
        if ($session->status === 'approved' && $exam->status === 'active') {
            $session->startSession();
            // Refresh the session model to get updated data
            $session->refresh();
        }

        // Check session status
        if (!$session->canContinueExam()) {
            return redirect()->route('exam.waiting-room', $session->id);
        }

        // Auto-finish if expired
        $session->checkAndFinishIfExpired();
        if (in_array($session->status, ['finished', 'timeout'])) {
            return redirect()->route('exam.result', $session->id);
        }

        // Get questions in order
        $questions = $exam->getQuestionsForStudent();
        
        // Get existing answers
        $existingAnswers = StudentAnswer::where('session_id', $session->id)
            ->get()
            ->keyBy('question_id')
            ->toArray();

        // Calculate progress (count each tier separately)
        $totalQuestions = $questions->count();
        $answeredTiers = 0;

        foreach ($questions as $question) {
            if (isset($existingAnswers[$question->id])) {
                $answer = $existingAnswers[$question->id];
                // Count each tier separately
                if (isset($answer['tier1_answer']) && $answer['tier1_answer'] !== null) {
                    $answeredTiers++;
                }
                if (isset($answer['tier2_answer']) && $answer['tier2_answer'] !== null) {
                    $answeredTiers++;
                }
            }
        }

        // Total items = questions * 2 (each question has 2 tiers)
        $totalItems = $totalQuestions * 2;
        $progress = $totalItems > 0 ? round(($answeredTiers / $totalItems) * 100) : 0;

        return view('student.exam', compact('session', 'exam', 'questions', 'existingAnswers', 'progress'));
    }

    public function submitAnswer(Request $request, StudentExamSession $session)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'tier1_answer' => 'nullable|integer|min:0|max:4',
            'tier2_answer' => 'nullable|integer|min:0|max:4',
        ]);

        // Check if session is still valid
        if (!$session->canContinueExam()) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi ujian tidak valid atau sudah berakhir.'
            ], 400);
        }

        // Verify question belongs to exam
        $examQuestion = ExamQuestion::where('exam_id', $session->exam_id)
            ->where('question_id', $request->question_id)
            ->first();

        if (!$examQuestion) {
            return response()->json([
                'success' => false,
                'message' => 'Soal tidak valid untuk ujian ini.'
            ], 400);
        }

        // Save or update answer
        $answer = StudentAnswer::updateOrCreate(
            [
                'session_id' => $session->id,
                'question_id' => $request->question_id
            ],
            [
                'tier1_answer' => $request->tier1_answer,
                'tier2_answer' => $request->tier2_answer,
            ]
        );

        // Calculate progress (count each tier separately)
        $totalQuestions = ExamQuestion::where('exam_id', $session->exam_id)->count();

        // Count answered tiers
        $answers = StudentAnswer::where('session_id', $session->id)->get();
        $answeredTiers = 0;
        foreach ($answers as $ans) {
            if ($ans->tier1_answer !== null) {
                $answeredTiers++;
            }
            if ($ans->tier2_answer !== null) {
                $answeredTiers++;
            }
        }

        // Total items = questions * 2 (each question has 2 tiers)
        $totalItems = $totalQuestions * 2;
        $progress = $totalItems > 0 ? round(($answeredTiers / $totalItems) * 100) : 0;
        $answeredQuestions = $answeredTiers / 2;

        return response()->json([
            'success' => true,
            'message' => 'Jawaban berhasil disimpan',
            'progress' => $progress,
            'answered_questions' => $answeredQuestions,
            'total_questions' => $totalQuestions,
            'remaining_time' => $session->remaining_time
        ]);
    }

    public function finishExam(Request $request, StudentExamSession $session)
    {
        // Check if session is valid
        if (!in_array($session->status, ['in_progress'])) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi ujian tidak valid.'
            ], 400);
        }

        $session->finishSession();

        return response()->json([
            'success' => true,
            'message' => 'Ujian selesai!',
            'redirect_url' => route('exam.result', $session->id)
        ]);
    }

    // ===== RESULTS =====
    public function showResult(StudentExamSession $session)
    {
        if (!in_array($session->status, ['finished', 'timeout'])) {
            return redirect()->route('exam.waiting-room', $session->id);
        }

        $exam = $session->exam;
        $answers = $session->studentAnswers()->with('question')->get();

        // Calculate detailed results
        $results = [
            'total_questions' => $answers->count(),
            'total_score' => $session->total_score ?? 0,
            'max_score' => $exam->total_points,
            'percentage' => $exam->total_points > 0 ? round(($session->total_score / $exam->total_points) * 100, 1) : 0,
            'breakdown' => $session->breakdown_summary,
            'duration' => $session->duration,
            'status' => $session->status,
        ];

        // Get breakdown and examResult for view compatibility
        $breakdown = $session->breakdown_summary;
        $examResult = (object) [
            'total_score' => $session->total_score ?? 0
        ];

        // Show detailed answers if exam allows it
        $showDetailedAnswers = $exam->show_result_immediately;

        return view('student.result', compact('session', 'exam', 'answers', 'results', 'showDetailedAnswers', 'breakdown', 'examResult'));
    }

    // ===== API ENDPOINTS =====
    public function markPageLoaded(StudentExamSession $session)
    {
        $session->markPageLoaded();
        
        // Refresh the session to get updated scheduled_finish_at
        $session->refresh();
        
        return response()->json([
            'success' => true,
            'message' => 'Timer started',
            'remaining_time' => $session->remaining_time,
            'total_duration' => $session->getTotalDurationMinutes() * 60,
            'scheduled_finish_at' => $session->scheduled_finish_at ? $session->scheduled_finish_at->toISOString() : null
        ]);
    }

    public function heartbeat(Request $request, StudentExamSession $session)
    {
        if ($session->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak aktif'
            ], 400);
        }

        // Auto-finish if expired
        $session->checkAndFinishIfExpired();

        if (in_array($session->status, ['finished', 'timeout'])) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu ujian habis',
                'redirect_url' => route('exam.result', $session->id)
            ]);
        }

        return response()->json([
            'success' => true,
            'remaining_time' => $session->remaining_time,
            'total_duration' => $session->getTotalDurationMinutes() * 60,
            'status' => $session->status
        ]);
    }

    public function getTimeRemaining(StudentExamSession $session)
    {
        if (!in_array($session->status, ['in_progress'])) {
            return response()->json([
                'remaining_time' => 0,
                'status' => $session->status
            ]);
        }

        // Auto-finish if expired
        $session->checkAndFinishIfExpired();

        return response()->json([
            'remaining_time' => $session->remaining_time,
            'total_duration' => $session->getTotalDurationMinutes() * 60,
            'extended_time' => $session->extended_time_minutes ?? 0,
            'status' => $session->status
        ]);
    }

    public function getExamStatus(Request $request)
    {
        $request->validate([
            'exam_code' => 'required|string|size:6'
        ]);

        $examCode = strtoupper($request->exam_code);
        
        $exam = Exam::where('current_code', $examCode)
                    ->orWhere('code', $examCode)
                    ->first();

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Kode ujian tidak valid'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'status' => $exam->status,
                'current_code' => $exam->getCurrentCode(),
                'duration_minutes' => $exam->duration_minutes,
                'total_questions' => $exam->total_questions
            ]
        ]);
    }

    // ===== UTILITY METHODS =====
    private function getDeviceTimezone()
    {
        // This will be implemented using JavaScript in the frontend
        // For now, return default timezone
        return 'Asia/Jakarta';
    }

    private function getDeviceInfo(Request $request)
    {
        return [
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'timestamp' => now()->toISOString()
        ];
    }
}
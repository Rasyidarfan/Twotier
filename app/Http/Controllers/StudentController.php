<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\StudentExamSession;
use App\Models\ExamResult;
use App\Models\StudentAnswer;
use App\Models\Question;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function showJoinForm()
    {
        return view('student.join');
    }

    public function joinExam(Request $request)
    {
        $request->validate([
            'exam_code' => 'required|string|size:6',
            'student_name' => 'required|string|max:255',
            'student_identifier' => 'nullable|string|max:50',
        ]);

        $exam = Exam::where('code', strtoupper($request->exam_code))->first();

        if (!$exam) {
            return back()->withErrors(['exam_code' => 'Kode ujian tidak ditemukan.']);
        }

        if ($exam->status === 'draft') {
            return back()->withErrors(['exam_code' => 'Ujian belum dimulai. Silakan tunggu instruksi dari guru.']);
        }

        if ($exam->status === 'finished') {
            return back()->withErrors(['exam_code' => 'Ujian sudah berakhir.']);
        }

        // Check if student already has a session
        $existingSession = StudentExamSession::where('exam_id', $exam->id)
            ->where('student_name', $request->student_name)
            ->first();

        if ($existingSession) {
            if ($existingSession->status === 'finished') {
                return back()->withErrors(['student_name' => 'Anda sudah menyelesaikan ujian ini.']);
            }
            
            // Continue existing session
            return redirect()->route('exam.take', $existingSession->id);
        }

        // Create new session
        $session = StudentExamSession::create([
            'exam_id' => $exam->id,
            'student_name' => $request->student_name,
            'student_identifier' => $request->student_identifier,
            'status' => 'registered',
        ]);

        // Create exam result record
        ExamResult::create([
            'exam_id' => $exam->id,
            'student_name' => $request->student_name,
            'student_identifier' => $request->student_identifier,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        if ($exam->status === 'waiting') {
            return view('student.waiting', compact('exam', 'session'));
        }

        return redirect()->route('exam.take', $session->id);
    }

    public function takeExam(StudentExamSession $session)
    {
        $exam = $session->exam;

        // Check if exam is active
        if ($exam->status !== 'active') {
            if ($exam->status === 'waiting') {
                return view('student.waiting', compact('exam', 'session'));
            }
            return redirect()->route('exam.join')->withErrors(['exam_code' => 'Ujian tidak aktif.']);
        }

        // Check if session is valid
        if ($session->status === 'finished') {
            return redirect()->route('exam.result', $session->id);
        }

        // Start session if not started
        if ($session->status === 'registered') {
            $session->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        // Check time limit
        $timeRemaining = $this->calculateTimeRemaining($exam, $session);
        if ($timeRemaining <= 0) {
            return $this->autoFinishExam($session);
        }

        // Get questions
        $examQuestions = $exam->examQuestions()
            ->with('question.chapter')
            ->orderBy('question_order')
            ->get();

        if ($exam->shuffle_questions && $session->status === 'in_progress') {
            $examQuestions = $examQuestions->shuffle();
        }

        // Get existing answers
        $existingAnswers = StudentAnswer::where('session_id', $session->id)
            ->pluck('tier2_answer', 'question_id')
            ->toArray();

        $currentQuestionIndex = 0;
        $answeredCount = count($existingAnswers);

        return view('student.exam', compact(
            'exam', 
            'session', 
            'examQuestions', 
            'existingAnswers', 
            'currentQuestionIndex',
            'answeredCount',
            'timeRemaining'
        ));
    }

    public function submitAnswer(Request $request, StudentExamSession $session)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'tier1_answer' => 'required|integer|between:0,4',
            'tier2_answer' => 'required|integer|between:0,4',
        ]);

        $exam = $session->exam;
        $question = Question::findOrFail($request->question_id);

        // Check if exam is still active and time hasn't expired
        $timeRemaining = $this->calculateTimeRemaining($exam, $session);
        if ($timeRemaining <= 0) {
            return response()->json(['error' => 'Waktu ujian telah habis.'], 400);
        }

        // Evaluate answer
        $resultCategory = $question->evaluateAnswer($request->tier1_answer, $request->tier2_answer);
        $pointsEarned = $question->calculatePoints($resultCategory, 10);

        // Get or create exam result
        $examResult = ExamResult::where('exam_id', $exam->id)
            ->where('student_name', $session->student_name)
            ->first();

        // Save or update answer
        StudentAnswer::updateOrCreate(
            [
                'session_id' => $session->id,
                'question_id' => $request->question_id,
            ],
            [
                'result_id' => $examResult->id,
                'tier1_answer' => $request->tier1_answer,
                'tier2_answer' => $request->tier2_answer,
                'result_category' => $resultCategory,
                'points_earned' => $pointsEarned,
            ]
        );

        return response()->json([
            'success' => true,
            'result_category' => $resultCategory,
            'points_earned' => $pointsEarned,
        ]);
    }

    public function finishExam(StudentExamSession $session)
    {
        if ($session->status === 'finished') {
            return redirect()->route('exam.result', $session->id);
        }

        $this->calculateFinalScore($session);

        $session->update([
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        return redirect()->route('exam.result', $session->id);
    }

    public function showResult(StudentExamSession $session)
    {
        if ($session->status !== 'finished') {
            return redirect()->route('exam.take', $session->id);
        }

        $exam = $session->exam;
        $examResult = ExamResult::where('exam_id', $exam->id)
            ->where('student_name', $session->student_name)
            ->first();

        $answers = StudentAnswer::where('session_id', $session->id)
            ->with('question')
            ->get();

        $breakdown = [
            'benar-benar' => $answers->where('result_category', 'benar-benar')->count(),
            'benar-salah' => $answers->where('result_category', 'benar-salah')->count(),
            'salah-benar' => $answers->where('result_category', 'salah-benar')->count(),
            'salah-salah' => $answers->where('result_category', 'salah-salah')->count(),
        ];

        return view('student.result', compact('exam', 'session', 'examResult', 'answers', 'breakdown'));
    }

    // API Methods for real-time features
    public function heartbeat(StudentExamSession $session)
    {
        $session->touch(); // Update updated_at timestamp
        
        $timeRemaining = $this->calculateTimeRemaining($session->exam, $session);
        
        return response()->json([
            'time_remaining' => $timeRemaining,
            'status' => $session->status,
        ]);
    }

    public function getTimeRemaining(StudentExamSession $session)
    {
        $timeRemaining = $this->calculateTimeRemaining($session->exam, $session);
        
        return response()->json([
            'time_remaining' => $timeRemaining,
        ]);
    }

    // Helper Methods
    private function calculateTimeRemaining($exam, $session)
    {
        if (!$session->started_at) {
            return $exam->duration_minutes * 60; // Full duration in seconds
        }

        $startTime = Carbon::parse($session->started_at);
        $endTime = $startTime->addMinutes($exam->duration_minutes);
        $now = Carbon::now();

        return max(0, $endTime->diffInSeconds($now, false));
    }

    private function autoFinishExam($session)
    {
        $this->calculateFinalScore($session);

        $session->update([
            'status' => 'timeout',
            'finished_at' => now(),
        ]);

        return redirect()->route('exam.result', $session->id)
            ->with('warning', 'Waktu ujian telah habis. Ujian diselesaikan secara otomatis.');
    }

    private function calculateFinalScore($session)
    {
        $answers = StudentAnswer::where('session_id', $session->id)->get();
        
        $breakdown = [
            'correct_correct' => $answers->where('result_category', 'benar-benar')->count(),
            'correct_wrong' => $answers->where('result_category', 'benar-salah')->count(),
            'wrong_correct' => $answers->where('result_category', 'salah-benar')->count(),
            'wrong_wrong' => $answers->where('result_category', 'salah-salah')->count(),
        ];

        $totalScore = $answers->sum('points_earned');

        // Update exam result
        $examResult = ExamResult::where('exam_id', $session->exam_id)
            ->where('student_name', $session->student_name)
            ->first();

        if ($examResult) {
            $examResult->update([
                'finished_at' => now(),
                'correct_correct' => $breakdown['correct_correct'],
                'correct_wrong' => $breakdown['correct_wrong'],
                'wrong_correct' => $breakdown['wrong_correct'],
                'wrong_wrong' => $breakdown['wrong_wrong'],
                'total_score' => $totalScore,
                'status' => 'completed',
            ]);
        }

        // Update session
        $session->update([
            'total_score' => $totalScore,
            'scoring_breakdown' => $breakdown,
        ]);

        return $totalScore;
    }
}

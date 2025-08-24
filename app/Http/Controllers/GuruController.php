<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\ExamQuestion;
use App\Models\StudentExamSession;
use App\Models\ExamResult;
use App\Models\StudentAnswer;

class GuruController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_exams' => Exam::where('created_by', auth()->id())->count(),
            'active_exams' => Exam::where('created_by', auth()->id())
                ->where('status', 'active')
                ->count(),
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

    // Exam Management
    public function exams()
    {
        $exams = Exam::where('created_by', auth()->id())
            ->with(['subject'])
            ->withCount('examQuestions')
            ->latest()
            ->paginate(15);

        return view('guru.exams.index', compact('exams'));
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

        return redirect()->route('guru.exams')
            ->with('success', 'Ujian berhasil dibuat dengan kode: ' . $code);
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

        return redirect()->route('guru.exams')
            ->with('success', 'Ujian berhasil diperbarui.');
    }

    public function deleteExam(Exam $exam)
    {
        $this->authorize('delete', $exam);
        
        $exam->delete();

        return redirect()->route('guru.exams')
            ->with('success', 'Ujian berhasil dihapus.');
    }

    // Exam Control
    public function waitingRoom(Exam $exam)
    {
        $this->authorize('view', $exam);

        $participants = StudentExamSession::where('exam_id', $exam->id)
            ->latest()
            ->get();

        return view('guru.exams.waiting-room', compact('exam', 'participants'));
    }

    public function startExam(Exam $exam)
    {
        $this->authorize('update', $exam);

        $exam->update([
            'status' => 'active',
            'start_time' => now(),
            'end_time' => now()->addMinutes($exam->duration_minutes),
        ]);

        return back()->with('success', 'Ujian telah dimulai!');
    }

    public function finishExam(Exam $exam)
    {
        $this->authorize('update', $exam);

        $exam->update([
            'status' => 'finished',
            'end_time' => now(),
        ]);

        // Auto-finish all active sessions
        StudentExamSession::where('exam_id', $exam->id)
            ->where('status', 'in_progress')
            ->update([
                'status' => 'finished',
                'finished_at' => now(),
            ]);

        return back()->with('success', 'Ujian telah diakhiri!');
    }

    // Results
    public function examResults(Exam $exam)
    {
        $this->authorize('view', $exam);

        $results = ExamResult::where('exam_id', $exam->id)
            ->with(['exam'])
            ->latest()
            ->get();

        $stats = [
            'total_participants' => $results->count(),
            'completed' => $results->where('status', 'completed')->count(),
            'average_score' => $results->where('status', 'completed')->avg('total_score'),
            'highest_score' => $results->where('status', 'completed')->max('total_score'),
            'lowest_score' => $results->where('status', 'completed')->min('total_score'),
        ];

        return view('guru.exams.results', compact('exam', 'results', 'stats'));
    }

    public function exportResults(Exam $exam)
    {
        $this->authorize('view', $exam);

        $results = ExamResult::where('exam_id', $exam->id)
            ->with(['exam'])
            ->get();

        // Simple CSV export
        $filename = 'hasil_ujian_' . $exam->code . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($results) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Nama Siswa',
                'Waktu Mulai',
                'Waktu Selesai',
                'Benar-Benar',
                'Benar-Salah',
                'Salah-Benar',
                'Salah-Salah',
                'Total Skor',
                'Status'
            ]);

            foreach ($results as $result) {
                fputcsv($file, [
                    $result->student_name,
                    $result->started_at,
                    $result->finished_at,
                    $result->correct_correct,
                    $result->correct_wrong,
                    $result->wrong_correct,
                    $result->wrong_wrong,
                    $result->total_score,
                    $result->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Question Bank
    public function questionBank()
    {
        $questions = Question::with(['chapter.subject', 'creator'])
            ->where('is_active', true)
            ->latest()
            ->paginate(20);

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
}

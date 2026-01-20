<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Chapter;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_questions' => Question::count(),
            'total_subjects' => Subject::count(),
            'total_chapters' => Chapter::count(),
            'active_questions' => Question::where('is_active', true)->count(),
            'recent_questions' => Question::with(['chapter.subject', 'creator'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // User Management
    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,guru',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,guru',
            'is_active' => 'boolean',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser(User $user)
    {
        // Prevent deleting the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil dihapus.');
    }

    // Question Management
    public function questions(Request $request)
    {
        $query = Question::with(['chapter.subject', 'creator']);

        // Handle filtering
        if ($request->filled('subject_id')) {
            $query->whereHas('chapter', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $questions = $query->latest()->paginate(15);

        $subjects = Subject::where('is_active', true)->get();
        $chapters = Chapter::where('is_active', true)->get();

        return view('admin.questions.index', compact('questions', 'subjects', 'chapters'));
    }

    public function createQuestion()
    {
        $subjects = Subject::where('is_active', true)->get();
        $chapters = Chapter::where('is_active', true)->get();

        return view('admin.questions.create', compact('subjects', 'chapters'));
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'tier1_question' => 'required|string',
            'tier1_options' => 'required|array|size:5',
            'tier1_options.*' => 'required|string',
            'tier1_correct_answer' => 'required|integer|between:0,4',
            'tier2_question' => 'required|string',
            'tier2_options' => 'required|array|size:5',
            'tier2_options.*' => 'required|string',
            'tier2_correct_answer' => 'required|integer|between:0,4',
            'difficulty' => 'required|in:mudah,sedang,sulit',
        ]);

        Question::create([
            'chapter_id' => $request->chapter_id,
            'tier1_question' => $request->tier1_question,
            'tier1_options' => $request->tier1_options,
            'tier1_correct_answer' => $request->tier1_correct_answer,
            'tier2_question' => $request->tier2_question,
            'tier2_options' => $request->tier2_options,
            'tier2_correct_answer' => $request->tier2_correct_answer,
            'difficulty' => $request->difficulty,
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function showQuestion(Question $question)
    {
        $question->load(['chapter.subject', 'creator']);
        return view('admin.questions.show', compact('question'));
    }

    public function editQuestion(Question $question)
    {
        $subjects = Subject::where('is_active', true)->get();
        $chapters = Chapter::where('is_active', true)->get();

        return view('admin.questions.edit', compact('question', 'subjects', 'chapters'));
    }

    public function updateQuestion(Request $request, Question $question)
    {
        $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'tier1_question' => 'required|string',
            'tier1_options' => 'required|array|size:5',
            'tier1_options.*' => 'required|string',
            'tier1_correct_answer' => 'required|integer|between:0,4',
            'tier2_question' => 'required|string',
            'tier2_options' => 'required|array|size:5',
            'tier2_options.*' => 'required|string',
            'tier2_correct_answer' => 'required|integer|between:0,4',
            'difficulty' => 'required|in:mudah,sedang,sulit',
            'is_active' => 'boolean',
        ]);

        $question->update([
            'chapter_id' => $request->chapter_id,
            'tier1_question' => $request->tier1_question,
            'tier1_options' => $request->tier1_options,
            'tier1_correct_answer' => $request->tier1_correct_answer,
            'tier2_question' => $request->tier2_question,
            'tier2_options' => $request->tier2_options,
            'tier2_correct_answer' => $request->tier2_correct_answer,
            'difficulty' => $request->difficulty,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function deleteQuestion(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Soal berhasil dihapus.');
    }

    public function filterQuestions(Request $request)
    {
        $query = Question::with(['chapter.subject', 'creator']);

        // Handle filtering
        if ($request->filled('subject_id')) {
            $query->whereHas('chapter', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tier1_question', 'like', "%{$search}%")
                  ->orWhere('tier2_question', 'like', "%{$search}%");
            });
        }

        $questions = $query->latest()->paginate(15);

        $subjects = Subject::where('is_active', true)->get();
        $chapters = Chapter::where('is_active', true)->get();

        return view('admin.questions.index', compact('questions', 'subjects', 'chapters'));
    }

    // Subject Management
    public function subjects()
    {
        $subjects = Subject::withCount('chapters')->latest()->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:subjects',
            'description' => 'nullable|string',
        ]);

        Subject::create($request->all());

        return back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function showSubject(Subject $subject)
    {
        $subject->load('chapters');
        return response()->json($subject);
    }

    public function editSubject(Subject $subject)
    {
        return response()->json($subject);
    }

    public function updateSubject(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $subject->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function deleteSubject(Subject $subject)
    {
        // Check if subject has chapters or questions
        if ($subject->chapters()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus mata pelajaran yang memiliki bab.');
        }

        $subject->delete();

        return back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    public function getSubjectChapters(Subject $subject)
    {
        $chapters = $subject->chapters()
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'grade', 'semester']);

        return response()->json($chapters);
    }

    // Chapter Management
    public function chapters(Request $request)
    {
        $query = Chapter::with('subject')->withCount('questions');

        // Apply filters if needed
        if ($request->filled('subject')) {
            $query->where('subject_id', $request->subject);
        }

        if ($request->filled('subject_filter')) {
            $query->where('subject_id', $request->subject_filter);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'order');
        if ($sortBy === 'order') {
            $query->orderBy('order', 'asc');
        } elseif ($sortBy === 'name') {
            $query->orderBy('name', 'asc');
        } elseif ($sortBy === 'created_at') {
            $query->latest();
        } elseif ($sortBy === 'questions_count') {
            $query->orderBy('questions_count', 'desc');
        } else {
            $query->orderBy('order', 'asc');
        }

        $chapters = $query->get();
        $subjects = Subject::where('is_active', true)->get();

        // Calculate additional data
        $totalQuestions = $chapters->sum('questions_count');

        // Get current subject if filtering by subject
        $currentSubject = null;
        if ($request->filled('subject')) {
            $currentSubject = Subject::find($request->subject);
        }

        return view('admin.chapters.index', compact('chapters', 'subjects', 'totalQuestions', 'currentSubject'));
    }

    public function storeChapter(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:10',
            'semester' => 'required|in:gasal,genap',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        Chapter::create($request->all());

        return back()->with('success', 'Bab berhasil ditambahkan.');
    }

    public function showChapter(Chapter $chapter)
    {
        $chapter->load(['subject', 'questions']);
        $chapter->questions_count = $chapter->questions()->count();
        return response()->json($chapter);
    }

    public function editChapter(Chapter $chapter)
    {
        $chapter->load('subject');
        return response()->json($chapter);
    }

    public function updateChapter(Request $request, Chapter $chapter)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:10',
            'semester' => 'required|in:gasal,genap',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $chapter->update([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'grade' => $request->grade,
            'semester' => $request->semester,
            'description' => $request->description,
            'order' => $request->order,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Bab berhasil diperbarui.');
    }

    public function deleteChapter(Chapter $chapter)
    {
        // Check if chapter has questions
        if ($chapter->questions()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus bab yang memiliki soal.');
        }

        $chapter->delete();

        return back()->with('success', 'Bab berhasil dihapus.');
    }

    public function moveChapter(Request $request, Chapter $chapter)
    {
        $request->validate([
            'direction' => 'required|in:up,down'
        ]);

        $direction = $request->direction;
        $currentOrder = $chapter->order ?? 0;

        if ($direction === 'up') {
            // Find chapter with order immediately above current
            $swapChapter = Chapter::where('subject_id', $chapter->subject_id)
                ->where('order', '<', $currentOrder)
                ->orderBy('order', 'desc')
                ->first();
        } else {
            // Find chapter with order immediately below current
            $swapChapter = Chapter::where('subject_id', $chapter->subject_id)
                ->where('order', '>', $currentOrder)
                ->orderBy('order', 'asc')
                ->first();
        }

        if ($swapChapter) {
            // Swap orders
            $tempOrder = $chapter->order;
            $chapter->update(['order' => $swapChapter->order]);
            $swapChapter->update(['order' => $tempOrder]);

            return response()->json(['success' => true, 'message' => 'Urutan bab berhasil diubah.']);
        }

        return response()->json(['success' => false, 'message' => 'Tidak dapat memindahkan bab.']);
    }

    /**
     * Edit student answer page (hidden admin route)
     */
    public function editStudentAnswer(\App\Models\StudentExamSession $session = null)
    {
        // If no session provided, show session selector
        if (!$session) {
            // Get recent sessions for selection
            $recentSessions = \App\Models\StudentExamSession::with('exam')
                ->where('status', 'finished')
                ->orderBy('finished_at', 'desc')
                ->limit(50)
                ->get();

            return view('admin.edit-student-answer-selector', [
                'recentSessions' => $recentSessions,
            ]);
        }

        // Load session with all related data
        $session->load([
            'exam.examQuestions.question',
            'studentAnswers.question'
        ]);

        // Get all answers with question details
        $answersData = [];
        foreach ($session->exam->examQuestions as $examQuestion) {
            $question = $examQuestion->question;
            $answer = $session->studentAnswers->firstWhere('question_id', $question->id);

            $answersData[] = [
                'answer_id' => $answer->id ?? null,
                'question_order' => $examQuestion->question_order,
                'question_id' => $question->id,
                'question_text' => strip_tags($question->tier1_question),
                'tier1_options' => $question->tier1_options,
                'tier2_options' => $question->tier2_options,
                'tier1_correct' => $question->tier1_correct_answer,
                'tier2_correct' => $question->tier2_correct_answer,
                'tier1_answer' => $answer->tier1_answer ?? null,
                'tier2_answer' => $answer->tier2_answer ?? null,
                'result_category' => $answer->result_category ?? null,
                'points_earned' => $answer->points_earned ?? 0,
                'base_points' => $examQuestion->points,
            ];
        }

        return view('admin.edit-student-answer', [
            'session' => $session,
            'answersData' => $answersData,
        ]);
    }

    /**
     * Update student answers
     */
    public function updateStudentAnswer(\Illuminate\Http\Request $request, \App\Models\StudentExamSession $session)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.tier1' => 'required|integer',
            'answers.*.tier2' => 'required|integer',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Load all answers with relations in one query (avoid N+1)
            $answerIds = array_keys($request->answers);
            $answers = \App\Models\StudentAnswer::with('question')
                ->whereIn('id', $answerIds)
                ->get()
                ->keyBy('id');

            // Load exam questions mapping in one query
            $questionIds = $answers->pluck('question_id')->unique();
            $examQuestions = $session->exam->examQuestions()
                ->whereIn('question_id', $questionIds)
                ->get()
                ->keyBy('question_id');

            $totalScore = 0;
            $breakdown = [
                'benar-benar' => 0,
                'benar-salah' => 0,
                'salah-benar' => 0,
                'salah-salah' => 0,
            ];

            // Update each answer
            foreach ($request->answers as $answerId => $data) {
                $answer = $answers->get($answerId);
                if (!$answer) continue;

                // Update tier answers
                $answer->tier1_answer = (int)$data['tier1'];
                $answer->tier2_answer = (int)$data['tier2'];

                // Recalculate category and points
                $question = $answer->question;
                $category = $question->evaluateAnswer((int)$data['tier1'], (int)$data['tier2']);

                // Get base points
                $examQuestion = $examQuestions->get($question->id);
                $basePoints = $examQuestion ? $examQuestion->points : 10;
                $points = $question->calculatePoints($category, $basePoints);

                $answer->result_category = $category;
                $answer->points_earned = $points;
                $answer->save();

                // Accumulate for session totals
                $totalScore += $points;
                $breakdown[$category]++;
            }

            // Update session totals directly (avoid recalculating)
            $session->update([
                'total_score' => $totalScore,
                'scoring_breakdown' => $breakdown,
            ]);

            // Clear item analysis cache for this exam
            \Illuminate\Support\Facades\Cache::forget("item_analysis_{$session->exam_id}");

            \Illuminate\Support\Facades\DB::commit();

            return redirect()
                ->route('admin.edit-student-answer', $session->id)
                ->with('success', 'Jawaban siswa berhasil diperbarui. Total skor: ' . $totalScore);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Recalculate all student scores based on their answers
     * This will recalculate result_category and points_earned for all student_answers
     * across all exams, and update total_score in student_exam_sessions
     */
    public function recalculateAllScores(\Illuminate\Http\Request $request)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $stats = [
                'sessions_processed' => 0,
                'answers_recalculated' => 0,
                'exams_affected' => [],
            ];

            // Get all sessions (optionally filter by exam_id if provided)
            $sessionsQuery = \App\Models\StudentExamSession::with(['exam.examQuestions', 'studentAnswers.question']);

            if ($request->filled('exam_id')) {
                $sessionsQuery->where('exam_id', $request->exam_id);
            }

            $sessions = $sessionsQuery->get();

            foreach ($sessions as $session) {
                // Load exam questions mapping for this exam
                $examQuestions = $session->exam->examQuestions->keyBy('question_id');

                $totalScore = 0;
                $breakdown = [
                    'benar-benar' => 0,
                    'benar-salah' => 0,
                    'salah-benar' => 0,
                    'salah-salah' => 0,
                ];

                // Recalculate each answer
                foreach ($session->studentAnswers as $answer) {
                    if (!$answer->question) {
                        continue; // Skip if question was deleted
                    }

                    // Skip if no answers recorded
                    if (is_null($answer->tier1_answer) || is_null($answer->tier2_answer)) {
                        continue;
                    }

                    $question = $answer->question;

                    // Recalculate category
                    $category = $question->evaluateAnswer(
                        (int)$answer->tier1_answer,
                        (int)$answer->tier2_answer
                    );

                    // Get base points from exam_questions
                    $examQuestion = $examQuestions->get($question->id);
                    $basePoints = $examQuestion ? $examQuestion->points : 10;

                    // Calculate points
                    $points = $question->calculatePoints($category, $basePoints);

                    // Update answer
                    $answer->result_category = $category;
                    $answer->points_earned = $points;
                    $answer->save();

                    // Accumulate for session totals
                    $totalScore += $points;
                    $breakdown[$category]++;

                    $stats['answers_recalculated']++;
                }

                // Update session totals
                $session->update([
                    'total_score' => $totalScore,
                    'scoring_breakdown' => $breakdown,
                ]);

                $stats['sessions_processed']++;

                // Track affected exams
                if (!in_array($session->exam_id, $stats['exams_affected'])) {
                    $stats['exams_affected'][] = $session->exam_id;
                }
            }

            // Clear item analysis cache for all affected exams
            foreach ($stats['exams_affected'] as $examId) {
                \Illuminate\Support\Facades\Cache::forget("item_analysis_{$examId}");
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()
                ->back()
                ->with('success', sprintf(
                    'Berhasil menghitung ulang skor! Sessions: %d, Jawaban: %d, Ujian: %d',
                    $stats['sessions_processed'],
                    $stats['answers_recalculated'],
                    count($stats['exams_affected'])
                ));

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghitung ulang skor: ' . $e->getMessage());
        }
    }
}

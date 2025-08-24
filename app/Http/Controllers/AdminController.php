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

    // Chapter Management
    public function chapters()
    {
        $chapters = Chapter::with('subject')->latest()->get();
        $subjects = Subject::where('is_active', true)->get();
        
        return view('admin.chapters.index', compact('chapters', 'subjects'));
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
}

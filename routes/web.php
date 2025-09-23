<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ExamController;

/*
|--------------------------------------------------------------------------
| Web Routes V2 - Improved Exam System
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('exam.join');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Student exam routes (no authentication required)
Route::prefix('exam')->name('exam.')->group(function () {
    // Join exam
    Route::get('/join', [StudentController::class, 'showJoinForm'])->name('join');
    Route::post('/join', [StudentController::class, 'joinExam'])->name('join.submit');
    
    // Waiting room and identity
    Route::get('/waiting-room/{session}', [StudentController::class, 'waitingRoom'])->name('waiting-room');
    Route::post('/identity/{session}', [StudentController::class, 'submitIdentity'])->name('submit-identity');
    Route::post('/select-existing/{session}', [StudentController::class, 'selectExistingParticipant'])->name('select-existing');
    
    // Start and take exam
    Route::post('/start/{session}', [StudentController::class, 'startExam'])->name('start');
    Route::get('/take/{session}', [StudentController::class, 'takeExam'])->name('take');
    Route::post('/answer/{session}', [StudentController::class, 'submitAnswer'])->name('answer');
    Route::post('/finish/{session}', [StudentController::class, 'finishExam'])->name('finish');
    
    // Results
    Route::get('/result/{session}', [StudentController::class, 'showResult'])->name('result');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // Question management
        Route::get('/questions', [AdminController::class, 'questions'])->name('questions.index');
        Route::get('/questions/create', [AdminController::class, 'createQuestion'])->name('questions.create');
        Route::post('/questions', [AdminController::class, 'storeQuestion'])->name('questions.store');
        Route::get('/questions/{question}', [AdminController::class, 'showQuestion'])->name('questions.show');
        Route::get('/questions/{question}/edit', [AdminController::class, 'editQuestion'])->name('questions.edit');
        Route::put('/questions/{question}', [AdminController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/questions/{question}', [AdminController::class, 'deleteQuestion'])->name('questions.delete');
        
        // Subject and Chapter management
        Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects.index');
        Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('subjects.store');
        Route::get('/subjects/{subject}', [AdminController::class, 'showSubject'])->name('subjects.show');
        Route::get('/subjects/{subject}/edit', [AdminController::class, 'editSubject'])->name('subjects.edit');
        Route::put('/subjects/{subject}', [AdminController::class, 'updateSubject'])->name('subjects.update');
        Route::delete('/subjects/{subject}', [AdminController::class, 'deleteSubject'])->name('subjects.delete');
        Route::get('/subjects/{subject}/chapters', [AdminController::class, 'getSubjectChapters'])->name('subjects.chapters');

        Route::get('/chapters', [AdminController::class, 'chapters'])->name('chapters.index');
        Route::post('/chapters', [AdminController::class, 'storeChapter'])->name('chapters.store');
        Route::get('/chapters/{chapter}', [AdminController::class, 'showChapter'])->name('chapters.show');
        Route::get('/chapters/{chapter}/edit', [AdminController::class, 'editChapter'])->name('chapters.edit');
        Route::put('/chapters/{chapter}', [AdminController::class, 'updateChapter'])->name('chapters.update');
        Route::delete('/chapters/{chapter}', [AdminController::class, 'deleteChapter'])->name('chapters.delete');
        Route::post('/chapters/{chapter}/move', [AdminController::class, 'moveChapter'])->name('chapters.move');
        
        // Question filtering
        Route::get('/questions/filter', [AdminController::class, 'filterQuestions'])->name('questions.filter');
    });
    
    // Guru routes
    Route::middleware(['role:guru,admin'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
        
        // ===== EXAM MANAGEMENT =====
        Route::get('/exams', [GuruController::class, 'exams'])->name('exams.index');
        Route::get('/exams/create', [GuruController::class, 'createExam'])->name('exams.create');
        Route::post('/exams', [GuruController::class, 'storeExam'])->name('exams.store');
        Route::get('/exams/{exam}', [GuruController::class, 'showExam'])->name('exams.show');
        Route::get('/exams/{exam}/edit', [GuruController::class, 'editExam'])->name('exams.edit');
        Route::put('/exams/{exam}', [GuruController::class, 'updateExam'])->name('exams.update');
        Route::delete('/exams/{exam}', [GuruController::class, 'deleteExam'])->name('exams.delete');
        Route::post('/exams/{exam}/duplicate', [GuruController::class, 'duplicateExam'])->name('exams.duplicate');
        
        // ===== EXAM STATUS CONTROL =====
        Route::post('/exams/{exam}/status', [GuruController::class, 'updateExamStatus'])->name('exams.status');
        
        // ===== WAITING ROOM & MONITORING =====
        Route::get('/exams/{exam}/waiting-room', [GuruController::class, 'waitingRoom'])->name('exams.waiting-room');
        Route::get('/exams/{exam}/participants', [GuruController::class, 'getParticipants'])->name('exams.participants');
        
        // ===== PARTICIPANT MANAGEMENT =====
        Route::post('/exams/{exam}/participants/{participant}/approve', [GuruController::class, 'approveParticipant'])->name('exams.participants.approve');
        Route::post('/exams/{exam}/participants/{participant}/kick', [GuruController::class, 'kickParticipant'])->name('exams.participants.kick');
        
        // ===== TIME MANAGEMENT =====
        Route::post('/exams/{exam}/extend-time', [GuruController::class, 'extendTime'])->name('exams.extend-time');
        Route::post('/exams/{exam}/participants/{participant}/extend-time', [GuruController::class, 'extendTime'])->name('exams.participants.extend-time');
        
        // ===== COMMUNICATION =====
        Route::post('/exams/{exam}/broadcast-message', [GuruController::class, 'broadcastMessage'])->name('exams.broadcast-message');
        Route::post('/exams/{exam}/end', [GuruController::class, 'endExam'])->name('exams.end');
        
        // ===== EXPORT & RESULTS =====
        Route::get('/exams/{exam}/export-current-results', [GuruController::class, 'exportCurrentResults'])->name('exams.export-current-results');
        Route::get('/exams/{exam}/results', [GuruController::class, 'examResults'])->name('exams.results');
        Route::get('/exams/{exam}/export', [GuruController::class, 'exportResults'])->name('exams.export');
        
        // ===== QUESTION BANK =====
        Route::get('/questions', [GuruController::class, 'questionBank'])->name('questions');
        Route::get('/questions/index', [GuruController::class, 'questionBank'])->name('questions.index');
        Route::get('/questions/filter', [GuruController::class, 'filterQuestions'])->name('questions.filter');
    });
});

// ===== API ROUTES =====

// API routes for real-time features (authenticated)
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('/exams/{exam}/status', [ExamController::class, 'getStatus']);
    Route::get('/exams/{exam}/participants', [ExamController::class, 'getParticipants']);
});

// API routes for student exam (no auth required)
Route::prefix('api/student')->group(function () {
    Route::post('/sessions/{session}/heartbeat', [StudentController::class, 'heartbeat']);
    Route::post('/sessions/{session}/page-loaded', [StudentController::class, 'markPageLoaded']);
    Route::get('/sessions/{session}/time-remaining', [StudentController::class, 'getTimeRemaining']);
    Route::get('/sessions/{session}/status', function($sessionId) {
        $session = \App\Models\StudentExamSession::find($sessionId);
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }
        return response()->json(['status' => $session->status]);
    });
});

// API routes for public exam information
Route::prefix('api')->group(function () {
    Route::get('/exam-status', [StudentController::class, 'getExamStatus']);
    Route::get('/ping', function() {
        return response()->json(['pong' => true]);
    });
});

// ===== FALLBACK ROUTES =====

// Backward compatibility (optional - can be removed after migration)
Route::get('/student/join', function() {
    return redirect()->route('exam.join');
});

Route::get('/student/exam/{session}', function($session) {
    return redirect()->route('exam.take', $session);
});

// 404 fallback
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
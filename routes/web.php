<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ExamController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Student exam routes (no authentication required)
Route::prefix('exam')->name('exam.')->group(function () {
    Route::get('/join', [StudentController::class, 'showJoinForm'])->name('join');
    Route::post('/join', [StudentController::class, 'joinExam'])->name('join.submit');
    Route::get('/take/{session}', [StudentController::class, 'takeExam'])->name('take');
    Route::post('/answer/{session}', [StudentController::class, 'submitAnswer'])->name('answer');
    Route::post('/finish/{session}', [StudentController::class, 'finishExam'])->name('finish');
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
        Route::get('/chapters', [AdminController::class, 'chapters'])->name('chapters.index');
        Route::post('/chapters', [AdminController::class, 'storeChapter'])->name('chapters.store');
        
        // Question filtering
        Route::get('/questions/filter', [AdminController::class, 'filterQuestions'])->name('questions.filter');
    });
    
    // Guru routes
    Route::middleware(['role:guru,admin'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
        
        // Exam management
        Route::get('/exams', [GuruController::class, 'exams'])->name('exams.index');
        Route::get('/exams/create', [GuruController::class, 'createExam'])->name('exams.create');
        Route::post('/exams', [GuruController::class, 'storeExam'])->name('exams.store');
        Route::get('/exams/{exam}/edit', [GuruController::class, 'editExam'])->name('exams.edit');
        Route::put('/exams/{exam}', [GuruController::class, 'updateExam'])->name('exams.update');
        Route::delete('/exams/{exam}', [GuruController::class, 'deleteExam'])->name('exams.delete');
        
        // Exam control
        Route::get('/exams/{exam}/waiting-room', [GuruController::class, 'waitingRoom'])->name('exams.waiting-room');
        Route::get('/exams/{exam}/participants', [GuruController::class, 'getParticipants'])->name('exams.participants');
        Route::post('/exams/{exam}/start', [GuruController::class, 'startExam'])->name('exams.start');
        Route::post('/exams/{exam}/finish', [GuruController::class, 'finishExam'])->name('exams.finish');
        
        // Additional waiting room functionality
        Route::post('/exams/{exam}/extend-time', [GuruController::class, 'extendTime'])->name('exams.extend-time');
        Route::post('/exams/{exam}/broadcast-message', [GuruController::class, 'broadcastMessage'])->name('exams.broadcast-message');
        Route::post('/exams/{exam}/end', [GuruController::class, 'endExam'])->name('exams.end');
        Route::get('/exams/{exam}/export-current-results', [GuruController::class, 'exportCurrentResults'])->name('exams.export-current-results');
        Route::post('/exams/{exam}/participants/{participant}/kick', [GuruController::class, 'kickParticipant'])->name('exams.kick-participant');
        
        // Results
        Route::get('/exams/{exam}/results', [GuruController::class, 'examResults'])->name('exams.results');
        Route::get('/exams/{exam}/export', [GuruController::class, 'exportResults'])->name('exams.export');
        
        // Question bank
        Route::get('/questions', [GuruController::class, 'questionBank'])->name('questions');
        Route::get('/questions/index', [GuruController::class, 'questionBank'])->name('questions.index');
        Route::get('/questions/filter', [GuruController::class, 'filterQuestions'])->name('questions.filter');
    });
});

// API routes for real-time features
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('/exams/{exam}/status', [ExamController::class, 'getStatus']);
    Route::get('/exams/{exam}/participants', [ExamController::class, 'getParticipants']);
});

// API routes for student exam (no auth required)
Route::prefix('api/student')->group(function () {
    Route::post('/sessions/{session}/heartbeat', [StudentController::class, 'heartbeat']);
    Route::get('/sessions/{session}/time-remaining', [StudentController::class, 'getTimeRemaining']);
});

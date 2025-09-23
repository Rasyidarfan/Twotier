@extends('layouts.student')

@section('title', 'Ujian - ' . $exam->title)

@php
    $arabicLetters = ['أ', 'ب', 'ج', 'د', 'هـ'];
@endphp

@section('content')
<div class="exam-container">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="exam-navbar">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <h5 class="navbar-brand mb-0">{{ $exam->title }}</h5>
                <span class="badge bg-light text-dark ms-2">{{ $session->student_name }}</span>
            </div>
            
            <div class="d-flex align-items-center d-none d-lg-flex">
                <!-- Progress -->
                <div class="me-4">
                    <small class="text-light">Progress:</small>
                    <div class="progress" style="width: 150px; height: 6px;">
                        <div class="progress-bar" id="progress-bar" role="progressbar" style="width: {{ $progress }}%"></div>
                    </div>
                    <small class="text-light" id="progress-text">{{ $progress }}%</small>
                </div>
                
                <!-- Timer -->
                <div class="me-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock me-2"></i>
                        <div>
                            <div class="fw-bold" id="timer">00:00:00</div>
                            <small class="opacity-75">Sisa waktu</small>
                        </div>
                    </div>
                </div>
                
                <!-- Finish Button -->
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#finishExamModal">
                    <i class="bi bi-check-lg"></i> إنهاء
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid" style="margin-top: 80px; padding-bottom: 100px;">
        <div class="row">
            <!-- Questions Navigation Sidebar -->
            <div class="col-lg-2 d-none d-lg-block">
                <div class="card sticky-top" style="top: 90px;">
                    <div class="card-header">
                        <h6 class="mb-0">تنقل الأسئلة</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2" id="question-nav">
                            @foreach($questions as $index => $question)
                                <div class="col-4">
                                    <button class="btn btn-outline-secondary btn-sm w-100 question-nav-btn" 
                                            data-question="{{ $index + 1 }}" 
                                            onclick="goToQuestion({{ $index + 1 }})">
                                        {{ $index + 1 }}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <span class="badge bg-success me-1"></span> مجاب<br>
                                <span class="badge bg-warning me-1"></span> غير مكتمل<br>
                                <span class="badge bg-secondary me-1"></span> غير مجاب
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Content -->
            <div class="col-lg-10">
                @foreach($questions as $index => $question)
                    <div class="question-container" id="question-{{ $index + 1 }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        السؤال {{ $index + 1 }} من {{ $questions->count() }}
                                    </h5>
                                    <div class="d-lg-none">
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#questionNavOffcanvas">
                                            <i class="bi bi-list"></i> تنقل
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Tier 1 Question -->
                                <div class="tier-section mb-4">
                                    <div class="tier-header bg-primary text-white p-3 rounded-top">
                                        <h6 class="mb-0"><i class="bi bi-layers"></i>المستوى الأول</h6>
                                    </div>
                                    <div class="tier-content border rounded-bottom p-3">
                                        <div class="question-text mb-3">
                                            {!! $question->tier1_question !!}
                                        </div>
                                        <div class="options">
                                            @php
                                                $tier1Options = is_string($question->tier1_options) ? 
                                                    json_decode($question->tier1_options, true) : 
                                                    $question->tier1_options;
                                            @endphp
                                            @if(is_array($tier1Options))
                                                @foreach($tier1Options as $optionIndex => $option)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input tier1-option" 
                                                               type="radio" 
                                                               name="tier1_q{{ $question->id }}" 
                                                               value="{{ $optionIndex }}" 
                                                               id="tier1_q{{ $question->id }}_{{ $optionIndex }}"
                                                               data-question-id="{{ $question->id }}"
                                                               data-tier="1">
                                                        <label class="form-check-label" for="tier1_q{{ $question->id }}_{{ $optionIndex }}">
                                                            {{ $arabicLetters[$optionIndex] ?? chr(65 + $optionIndex) }}. {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Tier 2 Question -->
                                <div class="tier-section">
                                    <div class="tier-header bg-success text-white p-3 rounded-top">
                                        <h6 class="mb-0"><i class="bi bi-layers"></i>المستوى الثاني</h6>
                                    </div>
                                    <div class="tier-content border rounded-bottom p-3">
                                        <div class="question-text mb-3">
                                            {!! $question->tier2_question !!}
                                        </div>
                                        <div class="options">
                                            @php
                                                $tier2Options = is_string($question->tier2_options) ? 
                                                    json_decode($question->tier2_options, true) : 
                                                    $question->tier2_options;
                                            @endphp
                                            @if(is_array($tier2Options))
                                                @foreach($tier2Options as $optionIndex => $option)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input tier2-option" 
                                                               type="radio" 
                                                               name="tier2_q{{ $question->id }}" 
                                                               value="{{ $optionIndex }}" 
                                                               id="tier2_q{{ $question->id }}_{{ $optionIndex }}"
                                                               data-question-id="{{ $question->id }}"
                                                               data-tier="2">
                                                        <label class="form-check-label" for="tier2_q{{ $question->id }}_{{ $optionIndex }}">
                                                            {{ $arabicLetters[$optionIndex] ?? chr(65 + $optionIndex) }}. {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between mt-4">
                                    <div>
                                        @if($index > 0)
                                            <button class="btn btn-outline-secondary" onclick="goToQuestion({{ $index }})">
                                                <i class="bi bi-chevron-left"></i> السابق
                                            </button>
                                        @endif
                                    </div>
                                    <div>
                                        @if($index < $questions->count() - 1)
                                            <button class="btn btn-primary" onclick="goToQuestion({{ $index + 2 }})">
                                                التالي <i class="bi bi-chevron-right"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="questionNavOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">تنقل الأسئلة</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row g-2" id="mobile-question-nav">
                @foreach($questions as $index => $question)
                    <div class="col-4">
                        <button class="btn btn-outline-secondary btn-sm w-100 question-nav-btn" 
                                data-question="{{ $index + 1 }}" 
                                onclick="goToQuestion({{ $index + 1 }}); bootstrap.Offcanvas.getInstance(document.getElementById('questionNavOffcanvas')).hide();">
                            {{ $index + 1 }}
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Status Bar (Mobile) -->
    <div class="d-lg-none fixed-bottom bg-white border-top p-3">
        <div class="row align-items-center">
            <div class="col-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock me-2"></i>
                    <div>
                        <div class="fw-bold small" id="mobile-timer">00:00:00</div>
                    </div>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" id="mobile-progress-bar" style="width: {{ $progress }}%"></div>
                </div>
                <small id="mobile-progress-text">{{ $progress }}%</small>
            </div>
            <div class="col-4 text-end">
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#finishExamModal">
                    إنهاء
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Finish Exam Modal -->
<div class="modal fade" id="finishExamModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إنهاء الامتحان</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>هل أنت متأكد من إنهاء الامتحان؟</strong>
                </div>
                <div class="mb-3">
                    <p>حالة أداءك:</p>
                    <ul>
                        <li>إجمالي الأسئلة: <strong>{{ $questions->count() }}</strong></li>
                        <li>الأسئلة المجابة بالكامل: <strong><span id="answered-questions">0</span></strong></li>
                        <li>التقدم: <strong><span id="current-progress">{{ $progress }}%</span></strong></li>
                        <li>الوقت المتبقي: <strong><span id="remaining-time">-</span></strong></li>
                    </ul>
                </div>
                <p class="text-muted">بعد إنهاء الامتحان، لا يمكنك تغيير الإجابات.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-warning" onclick="finishExam()">
                    <i class="bi bi-check-lg"></i> نعم، إنهاء الامتحان
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Auto-save indicator -->
<div id="save-indicator" class="position-fixed bottom-0 end-0 m-3" style="z-index: 1050;">
    <div class="toast" id="saveToast" role="alert">
        <div class="toast-body">
            <i class="bi bi-check-circle text-success"></i> تم حفظ الإجابة
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const sessionId = {{ $session->id }};
const totalQuestions = {{ $questions->count() }};
let currentQuestion = 1;
let remainingTime = 0; // Will be set by server
let timerInterval;
let heartbeatInterval;
let autoSaveTimeout;
let isTimerStarted = false;

// Existing answers
const existingAnswers = @json($existingAnswers);

$(document).ready(function() {
    initializeExam();
    loadExistingAnswers();
    bindEvents();
    
    // Start timer with a small delay to ensure DOM is ready
    setTimeout(function() {
        markPageLoadedAndStartTimer();
        startHeartbeat();
    }, 100);
});

function initializeExam() {
    // Initialize question navigation
    updateQuestionNavigation();
    
    // Set focus to first unanswered question
    const firstUnanswered = findFirstUnansweredQuestion();
    if (firstUnanswered > 0) {
        goToQuestion(firstUnanswered);
    }
}

function loadExistingAnswers() {
    // Load existing answers from server data
    Object.keys(existingAnswers).forEach(questionId => {
        const answer = existingAnswers[questionId];
        if (answer && answer.tier1_answer !== null && answer.tier1_answer !== undefined) {
            $(`input[name="tier1_q${questionId}"][value="${answer.tier1_answer}"]`).prop('checked', true);
        }
        
        if (answer && answer.tier2_answer !== null && answer.tier2_answer !== undefined) {
            $(`input[name="tier2_q${questionId}"][value="${answer.tier2_answer}"]`).prop('checked', true);
        }
    });
    
    updateProgress();
    updateQuestionNavigation();
}

function markPageLoadedAndStartTimer() {
    // Mark page as loaded and get server-based timer
    $.post(`/api/student/sessions/${sessionId}/page-loaded`, {
        _token: $('meta[name="csrf-token"]').attr('content')
    }).done(function(response) {
        if (response.success) {
            // Set remaining time from server
            remainingTime = response.remaining_time;
            isTimerStarted = true;
            startServerBasedTimer();
            console.log(`Timer started with ${remainingTime} seconds remaining`);
        }
    }).fail(function(xhr) {
        console.error('Failed to start timer:', xhr.responseJSON?.message || 'Unknown error');
        // Don't start client timer if server call fails
    });
}

function bindEvents() {
    // Handle answer selection
    $('.tier1-option, .tier2-option').on('change', function() {
        saveAnswer(this);
    });
    
    // Prevent accidental page leave
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = 'Apakah Anda yakin ingin meninggalkan halaman ujian?';
    });
    
    // Handle visibility change (tab switch detection)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            console.log('Tab switched - this might be logged for monitoring');
        }
    });
}

function goToQuestion(questionNumber) {
    // Hide all questions
    $('.question-container').hide();
    
    // Show target question
    $(`#question-${questionNumber}`).show();
    
    // Update current question
    currentQuestion = questionNumber;
    
    // Update navigation
    updateQuestionNavigation();
    
    // Scroll to top
    $('html, body').animate({ scrollTop: 0 }, 300);
}

function findFirstUnansweredQuestion() {
    for (let i = 1; i <= totalQuestions; i++) {
        const questionId = $(`#question-${i} input[data-tier="1"]`).first().data('question-id');
        if (questionId) {
            const tier1Answered = $(`input[name="tier1_q${questionId}"]:checked`).length > 0;
            const tier2Answered = $(`input[name="tier2_q${questionId}"]:checked`).length > 0;
            
            if (!tier1Answered || !tier2Answered) {
                return i;
            }
        }
    }
    return 1;
}

function saveAnswer(element) {
    const questionId = $(element).data('question-id');
    const tier = $(element).data('tier');
    const value = $(element).val();
    
    // Clear any existing timeout
    if (autoSaveTimeout) {
        clearTimeout(autoSaveTimeout);
    }
    
    // Set new timeout for auto-save
    autoSaveTimeout = setTimeout(() => {
        submitAnswer(questionId);
    }, 1000); // Save 1 second after user stops changing answers
}

function submitAnswer(questionId) {
    const tier1Answer = $(`input[name="tier1_q${questionId}"]:checked`).val();
    const tier2Answer = $(`input[name="tier2_q${questionId}"]:checked`).val();
    
    // Only submit if both tiers are answered
    if (tier1Answer !== undefined && tier2Answer !== undefined) {
        $.post(`/exam/answer/${sessionId}`, {
            question_id: questionId,
            tier1_answer: tier1Answer,
            tier2_answer: tier2Answer,
            _token: $('meta[name="csrf-token"]').attr('content')
        }).done(function(response) {
            if (response.success) {
                showSaveIndicator();
                updateProgressFromResponse(response);
                updateQuestionNavigation();
                
                // Update remaining time from server
                if (response.remaining_time !== undefined) {
                    remainingTime = response.remaining_time;
                    updateTimerDisplay();
                }
                console.log(response);
            }
        }).fail(function(xhr) {
            if (xhr.status === 400) {
                const response = xhr.responseJSON;
                if (response.message.includes('tidak valid') || response.message.includes('berakhir')) {
                    alert('Sesi ujian berakhir. Anda akan diarahkan ke halaman hasil.');
                    window.location.href = `/exam/result/${sessionId}`;
                }
            }
        });
    }
}

function updateProgressFromResponse(response) {
    if (response.progress !== undefined) {
        const progress = response.progress;
        $('#progress-bar, #mobile-progress-bar').css('width', progress + '%');
        $('#progress-text, #mobile-progress-text').text(progress + '%');
        $('#answered-questions').text(response.answered_questions || 0);
        $('#current-progress').text(progress + '%');
    }
}

function updateProgress() {
    let answeredQuestions = 0;
    
    // Count questions where both tiers are answered
    $('input[data-tier="1"]').each(function() {
        const questionId = $(this).data('question-id');
        const tier1Answered = $(`input[name="tier1_q${questionId}"]:checked`).length > 0;
        const tier2Answered = $(`input[name="tier2_q${questionId}"]:checked`).length > 0;
        
        if (tier1Answered && tier2Answered) {
            answeredQuestions++;
        }
    });
    
    const progress = Math.round((answeredQuestions / totalQuestions) * 100);
    
    $('#progress-bar, #mobile-progress-bar').css('width', progress + '%');
    $('#progress-text, #mobile-progress-text').text(progress + '%');
    $('#answered-questions').text(answeredQuestions);
    $('#current-progress').text(progress + '%');
}

function updateQuestionNavigation() {
    $('.question-nav-btn').each(function() {
        const questionNum = $(this).data('question');
        const questionId = $(`#question-${questionNum} input[data-tier="1"]`).first().data('question-id');
        
        // Reset classes
        $(this).removeClass('btn-success btn-warning btn-outline-secondary')
               .addClass('btn-outline-secondary');
        
        if (questionId) {
            const tier1Answered = $(`input[name="tier1_q${questionId}"]:checked`).length > 0;
            const tier2Answered = $(`input[name="tier2_q${questionId}"]:checked`).length > 0;
            
            if (tier1Answered && tier2Answered) {
                $(this).removeClass('btn-outline-secondary').addClass('btn-success');
            } else if (tier1Answered || tier2Answered) {
                $(this).removeClass('btn-outline-secondary').addClass('btn-warning');
            }
        }
        
        // Highlight current question
        if (questionNum === currentQuestion) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });
}

function startServerBasedTimer() {
    updateTimerDisplay();
    
    // Use a client-side countdown only for display purposes
    // The server maintains the authoritative time
    timerInterval = setInterval(function() {
        if (remainingTime > 0) {
            remainingTime--;
            updateTimerDisplay();
            
            if (remainingTime <= 0) {
                timeUp();
            }
        }
    }, 1000);
}

function updateTimerDisplay() {
    const hours = Math.floor(remainingTime / 3600);
    const minutes = Math.floor((remainingTime % 3600) / 60);
    const seconds = remainingTime % 60;
    
    const timeString = String(hours).padStart(2, '0') + ':' + 
                      String(minutes).padStart(2, '0') + ':' + 
                      String(seconds).padStart(2, '0');
    
    $('#timer, #mobile-timer').text(timeString);
    $('#remaining-time').text(timeString);
    
    // Change color when time is running low
    if (remainingTime <= 300) { // 5 minutes
        $('#timer, #mobile-timer').addClass('text-danger');
    } else if (remainingTime <= 600) { // 10 minutes
        $('#timer, #mobile-timer').addClass('text-warning');
    }
}

function startHeartbeat() {
    heartbeatInterval = setInterval(function() {
        $.post(`/api/student/sessions/${sessionId}/heartbeat`, {
            _token: $('meta[name="csrf-token"]').attr('content')
        }).done(function(response) {
            if (!response.success) {
                if (response.redirect_url) {
                    window.location.href = response.redirect_url;
                } else {
                    alert('Sesi ujian berakhir.');
                    location.reload();
                }
            } else {
                // Update remaining time from server
                if (response.remaining_time !== undefined) {
                    remainingTime = response.remaining_time;
                    updateTimerDisplay();
                }
            }
        }).fail(function() {
            console.log('Heartbeat failed');
        });
    }, 30000); // Every 30 seconds
}

function timeUp() {
    clearInterval(timerInterval);
    clearInterval(heartbeatInterval);
    
    alert('Waktu ujian habis! Ujian akan diselesaikan secara otomatis.');
    
    // Submit exam automatically
    finishExam();
}

function finishExam() {
    // Disable all form elements
    $('input, button').prop('disabled', true);
    
    $.post(`/exam/finish/${sessionId}`, {
        _token: $('meta[name="csrf-token"]').attr('content')
    }).done(function(response) {
        if (response.success) {
            window.removeEventListener('beforeunload', function() {});
            window.location.href = response.redirect_url;
        } else {
            alert('Terjadi kesalahan saat menyelesaikan ujian: ' + response.message);
            $('input, button').prop('disabled', false);
        }
    }).fail(function(xhr) {
        alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
        $('input, button').prop('disabled', false);
    });
}

function showSaveIndicator() {
    const toast = new bootstrap.Toast(document.getElementById('saveToast'));
    toast.show();
}

// Cleanup intervals on page unload
$(window).on('beforeunload', function() {
    if (timerInterval) clearInterval(timerInterval);
    if (heartbeatInterval) clearInterval(heartbeatInterval);
    if (autoSaveTimeout) clearTimeout(autoSaveTimeout);
});
</script>
@endpush

@push('styles')
<style>
.exam-container {
    background-color: #f8f9fa;
    min-height: 100vh;
}

/* Arabic text styling */
.arabic-text {
    direction: rtl;
    text-align: right;
    font-family: 'Amiri', 'Noto Sans Arabic', serif;
}

.modal-title {
    direction: rtl;
    text-align: right;
}

.question-container h5 {
    direction: rtl;
    text-align: right;
}

.question-container {
    direction: rtl;
    text-align: right;
}

.question-container .form-check {
    text-align: right;
    direction: rtl;
}

.question-container .form-check-label {
    text-align: right;
    direction: rtl;
}

.question-container .question-text {
    direction: rtl;
    text-align: right;
}

.question-container .tier-content {
    direction: rtl;
    text-align: right;
}

.question-container .options {
    direction: rtl;
    text-align: right;
}

.question-container .form-check-input {
    margin-left: 0.5rem;
    margin-right: 0;
    float: right;
}

.question-container .card-header {
    direction: rtl;
    text-align: right;
}

.tier-section {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    overflow: hidden;
}

.tier-header {
    margin: 0;
}

.tier-content {
    border-top: none !important;
    border-radius: 0 0 0.375rem 0.375rem !important;
    background-color: #ffffff;
}

.question-nav-btn {
    aspect-ratio: 1;
    font-size: 0.875rem;
}

.question-nav-btn.active {
    box-shadow: 0 0 0 2px #0d6efd;
}

.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
}

.avatar-lg {
    width: 64px;
    height: 64px;
    font-size: 1.5rem;
}

.avatar-xl {
    width: 80px;
    height: 80px;
    font-size: 2rem;
}

@media (max-width: 991.98px) {
    .exam-container {
        padding-bottom: 80px;
    }
    
    /* Hide desktop elements on mobile */
    .d-lg-flex {
        display: none !important;
    }
}
</style>
@endpush
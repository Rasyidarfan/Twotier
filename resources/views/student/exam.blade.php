@extends('layouts.app')

@section('title', 'Ujian - ' . $exam->title)

@push('styles')
<style>
    :root {
        --primary-color: #6777ef;
        --secondary-color: #e3eaff;
        --success-color: #47c363;
        --danger-color: #fc544b;
        --warning-color: #ffa426;
        --info-color: #3abaf4;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
    }

    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .exam-wrapper {
        display: flex;
        flex-direction: row-reverse;
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
        gap: 20px;
    }

    .exam-container {
        flex: 1;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .nav-container {
        width: 280px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .exam-header {
        background: linear-gradient(135deg, var(--primary-color), #5a67d8);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .exam-title {
        flex: 1;
        min-width: 200px;
    }

    .exam-title h4 {
        margin: 0 0 5px 0;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .exam-title p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .timer-container {
        display: flex;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.2);
        padding: 10px 15px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .timer-container i {
        margin-right: 8px;
    }

    .timer-container.warning {
        background-color: var(--warning-color);
        animation: pulse 1s infinite;
    }

    .timer-container.danger {
        background-color: var(--danger-color);
        animation: pulse 0.5s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .font-controls {
        display: flex;
        gap: 5px;
    }

    .font-btn {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }

    .font-btn:hover {
        background-color: rgba(255, 255, 255, 0.3);
    }

    .progress-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .progress-bar-container {
        width: 120px;
        height: 8px;
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background-color: var(--success-color);
        transition: width 0.3s ease;
    }

    .progress-text {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .exam-content {
        height: calc(100vh - 200px);
        overflow-y: auto;
        padding: 0;
    }

    .question-container {
        padding: 30px;
        border-bottom: 1px solid #eee;
        display: none;
    }

    .question-container.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .question-number {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
    }

    .question-badge {
        background-color: var(--secondary-color);
        color: var(--primary-color);
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .flag-btn {
        background: none;
        border: none;
        color: #ccc;
        font-size: 1.2rem;
        cursor: pointer;
        transition: color 0.2s;
    }

    .flag-btn.flagged {
        color: var(--warning-color);
    }

    .tier-section {
        margin-bottom: 30px;
    }

    .tier-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .tier-title.tier1 {
        color: var(--primary-color);
    }

    .tier-title.tier2 {
        color: var(--success-color);
    }

    .tier-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .tier1 .tier-icon {
        background-color: var(--primary-color);
    }

    .tier2 .tier-icon {
        background-color: var(--success-color);
    }

    .question-text {
        background-color: #f8f9ff;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 1.1rem;
        line-height: 1.6;
        text-align: right;
        direction: rtl;
        border-right: 4px solid var(--primary-color);
    }

    .tier2 .question-text {
        background-color: #f0fff4;
        border-right-color: var(--success-color);
    }

    .options-container {
        display: grid;
        gap: 12px;
    }

    .option-item {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 15px;
        text-align: right;
        direction: rtl;
    }

    .option-item:hover {
        border-color: var(--primary-color);
        background-color: var(--secondary-color);
    }

    .option-item.selected {
        border-color: var(--primary-color);
        background-color: var(--secondary-color);
        box-shadow: 0 2px 8px rgba(103, 119, 239, 0.2);
    }

    .option-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #f1f3f4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #666;
        flex-shrink: 0;
    }

    .option-item.selected .option-circle {
        background-color: var(--primary-color);
        color: white;
    }

    .option-text {
        flex: 1;
        font-size: 1rem;
        line-height: 1.5;
    }

    .tier2-section {
        opacity: 0.5;
        pointer-events: none;
        transition: all 0.3s ease;
    }

    .tier2-section.enabled {
        opacity: 1;
        pointer-events: auto;
    }

    .exam-footer {
        padding: 20px 30px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-btn {
        background-color: var(--info-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .nav-btn:hover:not(:disabled) {
        background-color: #2c9ad1;
        transform: translateY(-1px);
    }

    .nav-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .save-btn {
        background-color: var(--success-color);
    }

    .save-btn:hover:not(:disabled) {
        background-color: #3ea356;
    }

    .finish-btn {
        background-color: var(--danger-color);
    }

    .finish-btn:hover {
        background-color: #e03c3c;
    }

    /* Navigation Sidebar */
    .nav-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .nav-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
        margin-bottom: 25px;
    }

    .nav-item {
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 6px;
        background-color: var(--light-color);
        color: var(--dark-color);
        cursor: pointer;
        font-weight: 600;
        border: 2px solid #dee2e6;
        transition: all 0.2s;
    }

    .nav-item:hover {
        border-color: var(--primary-color);
        transform: translateY(-1px);
    }

    .nav-item.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .nav-item.answered {
        background-color: var(--success-color);
        color: white;
        border-color: var(--success-color);
    }

    .nav-item.flagged {
        background-color: var(--warning-color);
        color: white;
        border-color: var(--warning-color);
    }

    .submit-section {
        text-align: center;
    }

    .submit-btn {
        width: 100%;
        padding: 15px;
        background-color: var(--danger-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .submit-btn:hover {
        background-color: #e03c3c;
        transform: translateY(-1px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .exam-wrapper {
            flex-direction: column;
            padding: 10px;
        }

        .nav-container {
            width: 100%;
            order: -1;
            position: static;
        }

        .exam-header {
            flex-direction: column;
            text-align: center;
        }

        .exam-content {
            height: auto;
            max-height: 60vh;
        }

        .question-container {
            padding: 20px;
        }
    }

    /* Custom Scrollbar */
    .exam-content::-webkit-scrollbar {
        width: 6px;
    }

    .exam-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .exam-content::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 3px;
    }

    .exam-content::-webkit-scrollbar-thumb:hover {
        background: #5a67d8;
    }
</style>
@endpush

@section('content')
<div class="exam-wrapper">
    <!-- Main Exam Container -->
    <div class="exam-container">
        <!-- Exam Header -->
        <div class="exam-header">
            <div class="exam-title">
                <h4>{{ $exam->title }}</h4>
                <p>{{ $exam->subject->name }} - Kelas {{ $exam->grade }} {{ ucfirst($exam->semester) }}</p>
            </div>
            
            <div class="font-controls">
                <button class="font-btn" onclick="changeFontSize(false)" title="Perkecil Font">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="font-btn" onclick="changeFontSize(true)" title="Perbesar Font">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            
            <div class="timer-container" id="timer-display">
                <i class="fas fa-clock"></i>
                <span id="time-remaining">{{ gmdate('H:i:s', $timeRemaining) }}</span>
            </div>
            
            <div class="progress-info">
                <div class="progress-bar-container">
                    <div class="progress-bar" id="progress-bar" style="width: {{ ($answeredCount / $examQuestions->count()) * 100 }}%"></div>
                </div>
                <div class="progress-text">
                    <span id="answered-count">{{ $answeredCount }}</span> / {{ $examQuestions->count() }}
                </div>
            </div>
        </div>

        <!-- Exam Content -->
        <div class="exam-content">
            @foreach($examQuestions as $index => $examQuestion)
                <div class="question-container {{ $index === $currentQuestionIndex ? 'active' : '' }}" 
                     data-question-index="{{ $index }}" 
                     data-question-id="{{ $examQuestion->question_id }}">
                    
                    <!-- Question Header -->
                    <div class="question-header">
                        <div class="question-number">
                            <i class="fas fa-question-circle"></i>
                            Soal {{ $index + 1 }} dari {{ $examQuestions->count() }}
                        </div>
                        <div class="question-badge">{{ $examQuestion->question->chapter->name }}</div>
                        <button class="flag-btn" onclick="toggleFlag({{ $index }})" title="Tandai Soal">
                            <i class="fas fa-flag"></i>
                        </button>
                    </div>

                    <!-- Tier 1 Section -->
                    <div class="tier-section">
                        <div class="tier-title tier1">
                            <div class="tier-icon">1</div>
                            <span>Pertanyaan Utama</span>
                        </div>
                        
                        <div class="question-text">
                            {{ $examQuestion->question->tier1_question }}
                        </div>
                        
                        <div class="options-container">
                            @foreach($examQuestion->question->tier1_options as $optionIndex => $option)
                                <div class="option-item tier1-option" 
                                     data-question-id="{{ $examQuestion->question_id }}" 
                                     data-option="{{ $optionIndex }}"
                                     onclick="selectTier1Option({{ $examQuestion->question_id }}, {{ $optionIndex }})">
                                    <div class="option-circle">{{ chr(65 + $optionIndex) }}</div>
                                    <div class="option-text">{{ $option }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tier 2 Section -->
                    <div class="tier-section tier2-section" id="tier2-{{ $examQuestion->question_id }}">
                        <div class="tier-title tier2">
                            <div class="tier-icon">2</div>
                            <span>Alasan Pemilihan Jawaban</span>
                        </div>
                        
                        <div class="question-text">
                            {{ $examQuestion->question->tier2_question }}
                        </div>
                        
                        <div class="options-container">
                            @foreach($examQuestion->question->tier2_options as $optionIndex => $option)
                                <div class="option-item tier2-option" 
                                     data-question-id="{{ $examQuestion->question_id }}" 
                                     data-option="{{ $optionIndex }}"
                                     onclick="selectTier2Option({{ $examQuestion->question_id }}, {{ $optionIndex }})">
                                    <div class="option-circle">{{ $optionIndex + 1 }}</div>
                                    <div class="option-text">{{ $option }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Exam Footer -->
        <div class="exam-footer">
            <button class="nav-btn" id="prev-btn" onclick="navigateQuestion(-1)">
                <i class="fas fa-arrow-left"></i>
                Sebelumnya
            </button>
            
            <div class="footer-center">
                <button class="nav-btn save-btn" id="save-btn" onclick="saveCurrentAnswer()" disabled>
                    <i class="fas fa-save"></i>
                    Simpan Jawaban
                </button>
            </div>
            
            <div class="footer-right">
                @if($currentQuestionIndex === $examQuestions->count() - 1)
                    <button class="nav-btn finish-btn" onclick="showFinishModal()">
                        <i class="fas fa-flag-checkered"></i>
                        Selesai Ujian
                    </button>
                @else
                    <button class="nav-btn" id="next-btn" onclick="navigateQuestion(1)">
                        Selanjutnya
                        <i class="fas fa-arrow-right"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Navigation Sidebar -->
    <div class="nav-container">
        <div class="nav-title">
            <i class="fas fa-list"></i>
            Navigasi Soal
        </div>
        
        <div class="nav-grid" id="question-navigator">
            @foreach($examQuestions as $index => $examQuestion)
                <div class="nav-item {{ $index === $currentQuestionIndex ? 'active' : '' }}" 
                     data-question-index="{{ $index }}"
                     onclick="navigateToQuestion({{ $index }})"
                     title="Soal {{ $index + 1 }}">
                    {{ $index + 1 }}
                </div>
            @endforeach
        </div>
        
        <div class="submit-section">
            <button class="submit-btn" onclick="showFinishModal()">
                <i class="fas fa-paper-plane"></i>
                Kumpulkan Ujian
            </button>
        </div>
    </div>
</div>

<!-- Finish Exam Modal -->
<div class="modal fade" id="finishExamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-flag-checkered"></i>
                    Selesai Ujian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-question-circle text-warning" style="font-size: 3rem;"></i>
                </div>
                <p class="text-center mb-3">Apakah Anda yakin ingin menyelesaikan ujian?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Anda telah menjawab <strong><span id="final-answered-count">{{ $answeredCount }}</span></strong> 
                    dari <strong>{{ $examQuestions->count() }}</strong> soal.
                </div>
                <p class="text-muted small text-center">
                    Setelah dikumpulkan, Anda tidak dapat mengubah jawaban lagi.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <form method="POST" action="{{ route('exam.finish', $session) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-paper-plane"></i>
                        Ya, Kumpulkan Ujian
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables
let currentQuestionIndex = {{ $currentQuestionIndex }};
let totalQuestions = {{ $examQuestions->count() }};
let timeRemaining = {{ $timeRemaining }};
let answeredCount = {{ $answeredCount }};
let sessionId = {{ $session->id }};
let examQuestions = @json($examQuestions->pluck('question_id'));
let questionFlags = {};
let fontSize = 16;

// Timer functionality
function updateTimer() {
    if (timeRemaining <= 0) {
        clearInterval(timerInterval);
        alert('Waktu ujian telah habis! Ujian akan diselesaikan secara otomatis.');
        document.querySelector('#finishExamModal form').submit();
        return;
    }
    
    timeRemaining--;
    const hours = Math.floor(timeRemaining / 3600);
    const minutes = Math.floor((timeRemaining % 3600) / 60);
    const seconds = timeRemaining % 60;
    
    const timeDisplay = document.getElementById('time-remaining');
    const timerContainer = document.getElementById('timer-display');
    
    timeDisplay.textContent = 
        String(hours).padStart(2, '0') + ':' + 
        String(minutes).padStart(2, '0') + ':' + 
        String(seconds).padStart(2, '0');
    
    // Change timer color based on remaining time
    timerContainer.classList.remove('warning', 'danger');
    if (timeRemaining < 300) { // 5 minutes
        timerContainer.classList.add('danger');
    } else if (timeRemaining < 600) { // 10 minutes
        timerContainer.classList.add('warning');
    }
}

// Start timer
const timerInterval = setInterval(updateTimer, 1000);

// Font size control
function changeFontSize(increase) {
    if (increase) {
        fontSize = Math.min(24, fontSize + 2);
    } else {
        fontSize = Math.max(12, fontSize - 2);
    }
    
    document.querySelectorAll('.question-text, .option-text').forEach(element => {
        element.style.fontSize = fontSize + 'px';
    });
}

// Question navigation
function navigateToQuestion(index) {
    if (index < 0 || index >= totalQuestions) return;
    
    // Hide current question
    document.querySelectorAll('.question-container').forEach(container => {
        container.classList.remove('active');
    });
    
    // Show target question
    const targetQuestion = document.querySelector(`[data-question-index="${index}"]`);
    if (targetQuestion) {
        targetQuestion.classList.add('active');
    }
    
    // Update navigation
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    const navItem = document.querySelector(`[data-question-index="${index}"]`);
    if (navItem) {
        navItem.classList.add('active');
    }
    
    currentQuestionIndex = index;
    updateNavigationButtons();
    checkCurrentAnswers();
}

function navigateQuestion(direction) {
    const newIndex = currentQuestionIndex + direction;
    navigateToQuestion(newIndex);
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    
    if (prevBtn) prevBtn.disabled = currentQuestionIndex === 0;
    if (nextBtn) nextBtn.disabled = currentQuestionIndex === totalQuestions - 1;
}

// Answer selection
function selectTier1Option(questionId, optionIndex) {
    // Remove selection from other tier1 options for this question
    document.querySelectorAll(`[data-question-id="${questionId}"].tier1-option`).forEach(option => {
        option.classList.remove('selected');
    });
    
    // Select current option
    const selectedOption = document.querySelector(`[data-question-id="${questionId}"].tier1-option[data-option="${optionIndex}"]`);
    if (selectedOption) {
        selectedOption.classList.add('selected');
    }
    
    // Enable tier 2
    const tier2Section = document.getElementById(`tier2-${questionId}`);
    if (tier2Section) {
        tier2Section.classList.add('enabled');
    }
    
    checkCurrentAnswers();
}

function selectTier2Option(questionId, optionIndex) {
    // Remove selection from other tier2 options for this question
    document.querySelectorAll(`[data-question-id="${questionId}"].tier2-option`).forEach(option => {
        option.classList.remove('selected');
    });
    
    // Select current option
    const selectedOption = document.querySelector(`[data-question-id="${questionId}"].tier2-option[data-option="${optionIndex}"]`);
    if (selectedOption) {
        selectedOption.classList.add('selected');
    }
    
    checkCurrentAnswers();
}

// Check if current question is fully answered
function checkCurrentAnswers() {
    const questionId = examQuestions[currentQuestionIndex];
    const tier1Selected = document.querySelector(`[data-question-id="${questionId}"].tier1-option.selected`);
    const tier2Selected = document.querySelector(`[data-question-id="${questionId}"].tier2-option.selected`);
    
    const saveBtn = document.getElementById('save-btn');
    if (saveBtn) {
        saveBtn.disabled = !(tier1Selected && tier2Selected);
    }
}

// Save current answer
function saveCurrentAnswer() {
    const questionId = examQuestions[currentQuestionIndex];
    const tier1Selected = document.querySelector(`[data-question-id="${questionId}"].tier1-option.selected`);
    const tier2Selected = document.querySelector(`[data-question-id="${questionId}"].tier2-option.selected`);
    
    if (!tier1Selected || !tier2Selected) {
        alert('Silakan pilih jawaban untuk kedua pertanyaan!');
        return;
    }
    
    const tier1Answer = tier1Selected.getAttribute('data-option');
    const tier2Answer = tier2Selected.getAttribute('data-option');
    
    // Disable save button and show loading
    const saveBtn = document.getElementById('save-btn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    
    // Send AJAX request
    fetch(`/exam/answer/${sessionId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            question_id: questionId,
            tier1_answer: parseInt(tier1Answer),
            tier2_answer: parseInt(tier2Answer)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mark question as answered
            const navItem = document.querySelector(`.nav-item[data-question-index="${currentQuestionIndex}"]`);
            if (navItem) {
                navItem.classList.add('answered');
            }
            
            // Update answered count
            answeredCount++;
            document.getElementById('answered-count').textContent = answeredCount;
            document.getElementById('final-answered-count').textContent = answeredCount;
            
            // Update progress bar
            const progress = (answeredCount / totalQuestions) * 100;
            document.getElementById('progress-bar').style.width = progress + '%';
            
            // Reset save button
            saveBtn.innerHTML = '<i class="fas fa-check"></i> Tersimpan';
            saveBtn.classList.add('btn-success');
            
            // Auto navigate to next question after 1 second
            setTimeout(() => {
                if (currentQuestionIndex < totalQuestions - 1) {
                    navigateToQuestion(currentQuestionIndex + 1);
                }
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Jawaban';
                saveBtn.classList.remove('btn-success');
                saveBtn.disabled = true;
            }, 1500);
        } else {
            alert('Gagal menyimpan jawaban. Silakan coba lagi.');
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Jawaban';
            saveBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
        saveBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Jawaban';
        saveBtn.disabled = false;
    });
}

// Toggle flag for question
function toggleFlag(questionIndex) {
    const flagBtn = document.querySelector(`[data-question-index="${questionIndex}"] .flag-btn`);
    const navItem = document.querySelector(`.nav-item[data-question-index="${questionIndex}"]`);
    
    if (questionFlags[questionIndex]) {
        // Remove flag
        delete questionFlags[questionIndex];
        flagBtn.classList.remove('flagged');
        navItem.classList.remove('flagged');
    } else {
        // Add flag
        questionFlags[questionIndex] = true;
        flagBtn.classList.add('flagged');
        navItem.classList.add('flagged');
    }
}

// Show finish modal
function showFinishModal() {
    const modal = new bootstrap.Modal(document.getElementById('finishExamModal'));
    modal.show();
}

// Load existing answers on page load
function loadExistingAnswers() {
    @foreach($existingAnswers as $questionId => $answers)
        // Find question index
        const questionIndex = examQuestions.indexOf({{ $questionId }});
        if (questionIndex >= 0) {
            // Mark as answered in navigation
            const navItem = document.querySelector(`.nav-item[data-question-index="${questionIndex}"]`);
            if (navItem) {
                navItem.classList.add('answered');
            }
            
            // Set selected options (you would need to expand this based on your data structure)
            // This is a simplified version - you might need to adjust based on how you store answers
        }
    @endforeach
}

// Heartbeat to keep session alive
function sendHeartbeat() {
    fetch(`/api/student/sessions/${sessionId}/heartbeat`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).catch(error => {
        console.error('Heartbeat failed:', error);
    });
}

// Prevent accidental page refresh
window.addEventListener('beforeunload', function(e) {
    e.preventDefault();
    e.returnValue = 'Anda yakin ingin meninggalkan halaman ujian?';
});

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    updateNavigationButtons();
    checkCurrentAnswers();
    loadExistingAnswers();
    
    // Start heartbeat every 30 seconds
    setInterval(sendHeartbeat, 30000);
    
    // Initialize existing answers display
    @foreach($existingAnswers as $questionId => $tier2Answer)
        // This would need to be expanded to show both tier1 and tier2 answers
        // For now, just enable tier2 if there's an existing answer
        const tier2Section = document.getElementById('tier2-{{ $questionId }}');
        if (tier2Section) {
            tier2Section.classList.add('enabled');
        }
    @endforeach
});
</script>
@endpush

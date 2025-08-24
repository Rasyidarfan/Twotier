@extends('layouts.app')

@section('title', 'Menunggu Ujian - ' . $exam->title)

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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .waiting-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .waiting-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        padding: 40px;
        text-align: center;
        max-width: 600px;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .waiting-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-color), var(--success-color));
    }

    .waiting-icon {
        font-size: 4rem;
        color: var(--warning-color);
        margin-bottom: 20px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.7;
        }
    }

    .waiting-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 10px;
    }

    .waiting-subtitle {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 30px;
    }

    .exam-info {
        background: var(--light-color);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        border-left: 5px solid var(--primary-color);
    }

    .exam-info h4 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 1.3rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        text-align: left;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-icon {
        width: 35px;
        height: 35px;
        background: var(--secondary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 0.9rem;
    }

    .info-text {
        flex: 1;
    }

    .info-label {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 2px;
    }

    .info-value {
        font-weight: 600;
        color: var(--dark-color);
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--warning-color);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        animation: blink 1.5s infinite;
    }

    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0.3; }
    }

    .instructions {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: left;
    }

    .instructions h5 {
        color: #856404;
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .instructions ul {
        margin: 0;
        padding-left: 20px;
        color: #856404;
    }

    .instructions li {
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .student-info {
        background: var(--secondary-color);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .student-info i {
        color: var(--primary-color);
    }

    .student-name {
        font-weight: 600;
        color: var(--primary-color);
    }

    .refresh-btn {
        background: var(--info-color);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .refresh-btn:hover {
        background: #2c9ad1;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(60, 154, 244, 0.3);
    }

    .loading-dots {
        display: inline-flex;
        gap: 4px;
        margin-left: 10px;
    }

    .loading-dot {
        width: 6px;
        height: 6px;
        background: var(--primary-color);
        border-radius: 50%;
        animation: loading 1.4s infinite ease-in-out;
    }

    .loading-dot:nth-child(1) { animation-delay: -0.32s; }
    .loading-dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes loading {
        0%, 80%, 100% {
            transform: scale(0);
            opacity: 0.5;
        }
        40% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .waiting-card {
            padding: 30px 20px;
            margin: 10px;
        }

        .waiting-title {
            font-size: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .instructions {
            text-align: center;
        }

        .instructions ul {
            text-align: left;
        }
    }

    /* Animation for card entrance */
    .waiting-card {
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="waiting-container">
    <div class="waiting-card">
        <div class="waiting-icon">
            <i class="fas fa-hourglass-half"></i>
        </div>
        
        <h1 class="waiting-title">Menunggu Ujian Dimulai</h1>
        <p class="waiting-subtitle">Silakan tunggu hingga guru memulai ujian</p>
        
        <div class="status-indicator">
            <div class="status-dot"></div>
            <span>Menunggu Instruksi Guru</span>
        </div>
        
        <div class="student-info">
            <i class="fas fa-user"></i>
            <span>Nama: <span class="student-name">{{ $session->student_name }}</span></span>
        </div>
        
        <div class="exam-info">
            <h4>
                <i class="fas fa-clipboard-list"></i>
                {{ $exam->title }}
            </h4>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">Mata Pelajaran</div>
                        <div class="info-value">{{ $exam->subject->name }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">Kelas & Semester</div>
                        <div class="info-value">{{ $exam->grade }} {{ ucfirst($exam->semester) }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">Durasi</div>
                        <div class="info-value">{{ $exam->duration_minutes }} menit</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">Jumlah Soal</div>
                        <div class="info-value">{{ $exam->examQuestions->count() }} soal</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="instructions">
            <h5>
                <i class="fas fa-info-circle"></i>
                Petunjuk Ujian
            </h5>
            <ul>
                <li>Pastikan koneksi internet Anda stabil</li>
                <li>Siapkan alat tulis untuk coret-coretan (jika diperlukan)</li>
                <li>Ujian menggunakan sistem <strong>Two-Tier</strong> (2 pertanyaan per soal)</li>
                <li>Jawab pertanyaan utama terlebih dahulu, kemudian pilih alasan</li>
                <li>Pastikan menjawab kedua tier untuk mendapat nilai maksimal</li>
                <li>Waktu ujian akan dimulai setelah guru mengaktifkan ujian</li>
                <li>Jangan menutup atau refresh halaman selama ujian berlangsung</li>
            </ul>
        </div>
        
        <button class="refresh-btn" onclick="checkExamStatus()">
            <i class="fas fa-sync-alt" id="refresh-icon"></i>
            <span id="refresh-text">Periksa Status</span>
            <div class="loading-dots" id="loading-dots" style="display: none;">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
        </button>
        
        <div style="margin-top: 20px;">
            <small class="text-muted">
                <i class="fas fa-shield-alt"></i>
                Halaman ini akan otomatis refresh setiap 10 detik
            </small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let checkInterval;
let isChecking = false;

// Check exam status
function checkExamStatus() {
    if (isChecking) return;
    
    isChecking = true;
    const refreshIcon = document.getElementById('refresh-icon');
    const refreshText = document.getElementById('refresh-text');
    const loadingDots = document.getElementById('loading-dots');
    
    // Show loading state
    refreshIcon.style.display = 'none';
    refreshText.textContent = 'Memeriksa';
    loadingDots.style.display = 'flex';
    
    // Make request to check exam status
    fetch(`/api/exams/{{ $exam->id }}/status`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'active') {
            // Exam has started, redirect to exam page
            window.location.href = `/exam/take/{{ $session->id }}`;
        } else if (data.status === 'finished') {
            // Exam has ended
            alert('Ujian telah berakhir.');
            window.location.href = `/exam/join`;
        } else {
            // Still waiting
            showStatusMessage('Ujian belum dimulai. Tetap menunggu...', 'info');
        }
    })
    .catch(error => {
        console.error('Error checking exam status:', error);
        showStatusMessage('Gagal memeriksa status ujian.', 'error');
    })
    .finally(() => {
        // Reset button state
        setTimeout(() => {
            refreshIcon.style.display = 'inline';
            refreshText.textContent = 'Periksa Status';
            loadingDots.style.display = 'none';
            isChecking = false;
        }, 1000);
    });
}

// Show status message
function showStatusMessage(message, type) {
    // Remove existing messages
    const existingMessage = document.querySelector('.status-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.className = `status-message alert alert-${type === 'error' ? 'danger' : 'info'}`;
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        min-width: 300px;
        animation: slideInRight 0.3s ease;
    `;
    messageDiv.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
        ${message}
    `;
    
    document.body.appendChild(messageDiv);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 300);
        }
    }, 3000);
}

// Auto-refresh every 10 seconds
function startAutoRefresh() {
    checkInterval = setInterval(() => {
        if (!isChecking) {
            checkExamStatus();
        }
    }, 10000);
}

// Stop auto-refresh
function stopAutoRefresh() {
    if (checkInterval) {
        clearInterval(checkInterval);
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Start auto-refresh
    startAutoRefresh();
    
    // Initial status check after 2 seconds
    setTimeout(checkExamStatus, 2000);
    
    // Stop auto-refresh when page is about to unload
    window.addEventListener('beforeunload', stopAutoRefresh);
    
    // Handle visibility change (when user switches tabs)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
            checkExamStatus();
        }
    });
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush

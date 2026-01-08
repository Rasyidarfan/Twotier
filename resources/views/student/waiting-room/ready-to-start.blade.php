@extends('layouts.student')

@section('title', 'Siap Mulai - ' . $exam->title)

@section('content')
<div class="min-vh-100 bg-light py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h3 class="text-primary">{{ $exam->title }}</h3>
                        <p class="text-muted mb-0">{{ $exam->subject->name }} • {{ $exam->grade }} • {{ ucfirst($exam->semester) }}</p>
                        <div class="mt-2">
                            <span class="badge bg-success fs-6">DISETUJUI - SIAP MULAI</span>
                        </div>
                    </div>
                </div>

                <!-- Ready to Start Card -->
                <div class="card {{ $exam->status === 'active' ? 'border-success' : 'border-warning' }}">
                    <div class="card-header {{ $exam->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }} text-white text-center">
                        <h4 class="mb-0">
                            @if($exam->status === 'active')
                                <i class="bi bi-check-circle-fill"></i> Anda Telah Disetujui!
                            @else
                                <i class="bi bi-clock-fill"></i> Disetujui - Menunggu Ujian Dimulai
                            @endif
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="avatar-xl bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                <i class="bi bi-person-check" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="text-success">Selamat, {{ $session->student_name }}!</h4>
                            <p class="text-muted mb-0">Partisipasi Anda telah disetujui oleh guru.</p>
                            @if($session->approved_at)
                                <small class="text-muted">
                                    Disetujui pada {{ $session->approved_at->format('H:i:s') }}
                                </small>
                            @endif
                        </div>

                        <!-- Error Message -->
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Start Button -->
                        <div class="d-grid mb-4">
                            @if($exam->status === 'active')
                                <form method="POST" action="{{ route('exam.start', $session) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg" id="startButton">
                                        <i class="bi bi-play-fill"></i> Mulai Ujian Sekarang
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-secondary btn-lg" disabled id="startButton">
                                    <i class="bi bi-clock"></i> Menunggu Guru Memulai Ujian
                                </button>
                                <small class="text-muted text-center mt-2 d-block">
                                    Status ujian: <span class="badge bg-warning">{{ $exam->status_display }}</span>
                                </small>
                            @endif
                        </div>

                        <!-- Exam Info -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3"><i class="bi bi-info-circle"></i> Detail Ujian</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <strong>Durasi:</strong> {{ $exam->duration_minutes }} menit
                                                @if($session->extended_time_minutes > 0)
                                                    <br><small class="text-success">
                                                        <i class="bi bi-plus"></i> Waktu tambahan: {{ $session->extended_time_minutes }} menit
                                                    </small>
                                                @endif
                                            </li>
                                            <li class="mb-2"><strong>Jumlah Soal:</strong> {{ $exam->total_questions }} soal</li>
                                            <li class="mb-2"><strong>Sistem:</strong> Two-Tier Testing</li>
                                            <li class="mb-0"><strong>Acak Soal:</strong> {{ $exam->shuffle_questions ? 'Ya' : 'Tidak' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3"><i class="bi bi-person"></i> Informasi Anda</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2"><strong>Nama:</strong> {{ $session->student_name }}</li>
                                            @if($session->student_identifier)
                                                <li class="mb-2"><strong>NIS:</strong> {{ $session->student_identifier }}</li>
                                            @endif
                                            <li class="mb-2"><strong>Zona Waktu:</strong> {{ $session->timezone }}</li>
                                            <li class="mb-0"><strong>Status:</strong> <span class="text-success">Siap mulai</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions Card -->
                <div class="card mt-4 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> Petunjuk Penting Sebelum Memulai</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Sistem Two-Tier Testing:</h6>
                                <ul class="small">
                                    <li>Setiap soal memiliki 2 tingkat pertanyaan</li>
                                    <li>Jawab tingkat 1 terlebih dahulu, lalu tingkat 2</li>
                                    <li>Penilaian berdasarkan kombinasi kedua jawaban</li>
                                    <li>Skor terbaik untuk jawaban benar-benar</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Aturan Ujian:</h6>
                                <ul class="small">
                                    <li>Waktu ujian dimulai setelah tombol "Mulai Ujian"</li>
                                    <li>Jawaban tersimpan otomatis</li>
                                    <li>Jangan refresh atau tutup halaman</li>
                                    <li>Ujian berakhir otomatis saat waktu habis</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-lightbulb"></i>
                            <strong>Tips:</strong> Pastikan koneksi internet stabil dan baca soal dengan teliti. 
                            Anda dapat mengubah jawaban sampai waktu berakhir.
                        </div>
                    </div>
                </div>

                <!-- Connection Status -->
                <div class="card mt-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-wifi text-success me-3" style="font-size: 2rem;"></i>
                            <div>
                                <h6 class="mb-0 text-success">Koneksi Stabil</h6>
                                <small class="text-muted">Status koneksi akan dipantau selama ujian</small>
                            </div>
                            <div class="ms-auto">
                                <div class="spinner-border spinner-border-sm text-success d-none" id="connectionSpinner"></div>
                                <span class="badge bg-success" id="connectionStatus">Online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Check connection status periodically
    setInterval(checkConnection, 10000);
    
    // Focus on start button
    $('#startButton').focus();
    
    // Prevent double submission
    $('#startButton').closest('form').on('submit', function() {
        $('#startButton').prop('disabled', true).html('<i class="bi bi-arrow-clockwise" style="animation: spin 1s linear infinite;"></i> Memulai ujian...');
    });
    
    // Check if exam status changed
    setInterval(function() {
        $.get(`/api/exam-status?exam_code={{ $exam->getCurrentCode() }}`, function(data) {
            if (data.success) {
                if (data.exam.status === 'active') {
                    // Exam started by teacher, redirect immediately to exam page
                    window.location.href = `/exam/take/{{ $session->id }}`;
                } else if (data.exam.status === 'finished') {
                    // Exam finished, show message
                    alert('Ujian telah diakhiri oleh guru.');
                    location.reload();
                }
            }
        }).fail(function() {
            // Handle error silently
        });
    }, 2000); // Check every 2 seconds for faster response
});

function checkConnection() {
    $('#connectionSpinner').removeClass('d-none');
    
    fetch('/api/ping', { method: 'HEAD' })
        .then(response => {
            $('#connectionStatus').removeClass('bg-danger').addClass('bg-success').text('Online');
        })
        .catch(error => {
            $('#connectionStatus').removeClass('bg-success').addClass('bg-danger').text('Offline');
        })
        .finally(() => {
            $('#connectionSpinner').addClass('d-none');
        });
}
</script>
@endpush
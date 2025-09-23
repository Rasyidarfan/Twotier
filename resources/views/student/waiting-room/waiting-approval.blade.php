@extends('layouts.student')

@section('title', 'Menunggu Persetujuan - ' . $exam->title)

@section('content')
<div class="min-vh-100 bg-light py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h3 class="text-primary">{{ $exam->title }}</h3>
                        <p class="text-muted mb-0">{{ $exam->subject->name }} • {{ $exam->grade }} • {{ ucfirst($exam->semester) }}</p>
                        <div class="mt-2">
                            <span class="badge bg-success fs-6">UJIAN SEDANG BERLANGSUNG</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Your Identity -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-user"></i> Identitas Anda</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0">{{ $session->student_name }}</h4>
                                        @if($session->student_identifier)
                                            <p class="text-muted mb-0">{{ $session->student_identifier }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning">
                                    <i class="fas fa-clock fa-spin"></i>
                                    <strong>Menunggu persetujuan guru...</strong>
                                    <br><small>Guru perlu menyetujui partisipasi Anda karena ujian sudah dimulai.</small>
                                </div>

                                <div class="text-muted small">
                                    <div class="mb-1">
                                        <i class="fas fa-clock"></i> 
                                        Bergabung pada: {{ $session->joined_at->format('H:i:s') }}
                                    </div>
                                    <div>
                                        <i class="fas fa-globe"></i> 
                                        Zona waktu: {{ $session->timezone }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Option to Select Existing Session -->
                        @if($session->student_identifier)
                            @php
                                $existingSessions = $activeParticipants->where('student_identifier', $session->student_identifier)
                                                                    ->where('id', '!=', $session->id);
                            @endphp
                            @if($existingSessions->count() > 0)
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-history"></i> Sesi Sebelumnya Ditemukan</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="small mb-3">Kami menemukan sesi ujian yang mungkin milik Anda. Pilih untuk melanjutkan:</p>
                                        
                                        @foreach($existingSessions as $existingSession)
                                            <div class="card mb-2 border-success">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <strong>{{ $existingSession->student_name }}</strong>
                                                            <br><small class="text-muted">{{ $existingSession->student_identifier }}</small>
                                                            <br><small class="text-success">
                                                                Status: {{ $existingSession->status_display }}
                                                                @if($existingSession->started_at)
                                                                    • Mulai: {{ $existingSession->getStartTimeFormatted() }}
                                                                @endif
                                                            </small>
                                                        </div>
                                                        <form method="POST" action="{{ route('exam.select-existing', $session) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="existing_session_id" value="{{ $existingSession->id }}">
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fas fa-arrow-right"></i> Lanjutkan
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="col-md-6">
                        <!-- Active Participants -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-users"></i> 
                                    Peserta yang Sedang Mengerjakan ({{ $activeParticipants->count() }})
                                </h6>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                @if($activeParticipants->count() > 0)
                                    @foreach($activeParticipants as $participant)
                                        <div class="d-flex align-items-center p-2 mb-2 {{ $participant->status === 'in_progress' ? 'bg-success bg-opacity-10' : 'bg-light' }} rounded">
                                            <div class="avatar-sm 
                                                        {{ $participant->status === 'in_progress' ? 'bg-success' : 'bg-primary' }} 
                                                        text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                {{ strtoupper(substr($participant->student_name, 0, 1)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold">{{ $participant->student_name }}</div>
                                                @if($participant->student_identifier)
                                                    <small class="text-muted">{{ $participant->student_identifier }}</small>
                                                @endif
                                                <div>
                                                    <small class="
                                                           {{ $participant->status === 'in_progress' ? 'text-success' : 'text-info' }}">
                                                        {{ $participant->status_display }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if($participant->status === 'in_progress')
                                                    <small class="text-success">
                                                        <i class="fas fa-circle"></i> Aktif
                                                    </small>
                                                @else
                                                    <small class="text-muted">
                                                        <i class="fas fa-check-circle"></i> Siap
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <br>Belum ada peserta lain
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card mt-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-info fa-2x me-3 mt-1"></i>
                            <div>
                                <h6 class="text-info mb-2">Informasi Ujian</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled mb-0 small">
                                            <li><strong>Durasi:</strong> {{ $exam->duration_minutes }} menit</li>
                                            <li><strong>Jumlah Soal:</strong> {{ $exam->total_questions }} soal</li>
                                            <li><strong>Sistem:</strong> Two-Tier Testing</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled mb-0 small">
                                            <li><strong>Status:</strong> Ujian sedang berlangsung</li>
                                            <li><strong>Persetujuan:</strong> Diperlukan untuk peserta yang terlambat</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded">
                                    <small class="text-dark">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                        <strong>Penting:</strong> Setelah disetujui, Anda dapat langsung memulai ujian. 
                                        Pastikan koneksi internet stabil dan jangan menutup halaman browser.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto refresh to check for approval status
    setInterval(checkApprovalStatus, 5000); // Check every 5 seconds
});

function checkApprovalStatus() {
    // Check both session status and exam status
    $.get(`/api/student/sessions/{{ $session->id }}/status`, function(response) {
        if (response.status === 'approved') {
            location.reload();
        }
    }).fail(function() {
        // Ignore errors, just keep trying
    });
    
    // Also check if exam is started and automatically redirect to exam
    $.get(`/api/exam-status?exam_code={{ $exam->getCurrentCode() }}`, function(data) {
        if (data.success && data.exam.status === 'active') {
            // Check if we're approved, if so go directly to exam
            $.get(`/api/student/sessions/{{ $session->id }}/status`, function(response) {
                if (response.status === 'approved') {
                    window.location.href = `/exam/take/{{ $session->id }}`;
                }
            });
        }
    }).fail(function() {
        // Handle error silently
    });
}
</script>
@endpush
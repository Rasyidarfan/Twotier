@extends('layouts.student')

@section('title', 'Isi Identitas - ' . $exam->title)

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
                            <span class="badge bg-warning fs-6">UJIAN MENUNGGU DIMULAI</span>
                        </div>
                    </div>
                </div>

                @if($session->student_name)
                    <!-- Identity Already Filled -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-check-circle"></i> Identitas Sudah Tersimpan</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-lg bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $session->student_name }}</h4>
                                    @if($session->student_identifier)
                                        <p class="text-muted mb-0">{{ $session->student_identifier }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Status:</strong> Identitas berhasil tersimpan. Anda sudah berada dalam waiting room ujian.
                                <br><small>Tunggu guru memulai ujian atau memberikan persetujuan jika ujian sudah dimulai.</small>
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
                @else
                    <!-- Identity Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Isi Identitas Anda</h5>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('exam.submit-identity', $session) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="student_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('student_name') is-invalid @enderror" 
                                               id="student_name" 
                                               name="student_name" 
                                               value="{{ old('student_name', $session->student_name) }}" 
                                               placeholder="Masukkan nama lengkap Anda"
                                               maxlength="255" 
                                               required>
                                        @error('student_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="student_identifier" class="form-label">Nomor Induk/NIS (Opsional)</label>
                                        <input type="text" 
                                               class="form-control @error('student_identifier') is-invalid @enderror" 
                                               id="student_identifier" 
                                               name="student_identifier" 
                                               value="{{ old('student_identifier', $session->student_identifier) }}" 
                                               placeholder="Masukkan NIS atau nomor identitas"
                                               maxlength="255">
                                        @error('student_identifier')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-check"></i> Simpan Identitas
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Other Participants -->
                @if($otherParticipants->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-users"></i> 
                            Peserta Lain yang Sudah Bergabung ({{ $otherParticipants->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($otherParticipants as $participant)
                                <div class="col-md-4 mb-2">
                                    <div class="d-flex align-items-center p-2 bg-light rounded">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($participant->student_name, 0, 1)) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ $participant->student_name }}</div>
                                            @if($participant->student_identifier)
                                                <small class="text-muted">{{ $participant->student_identifier }}</small>
                                            @endif
                                        </div>
                                        <small class="text-success">
                                            <i class="fas fa-circle"></i>
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Info Card -->
                <div class="card mt-4 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-info fa-2x me-3 mt-1"></i>
                            <div>
                                <h6 class="text-info mb-2">Informasi Ujian</h6>
                                <ul class="list-unstyled mb-0 small">
                                    <li><strong>Durasi:</strong> {{ $exam->duration_minutes }} menit</li>
                                    <li><strong>Jumlah Soal:</strong> {{ $exam->total_questions }} soal</li>
                                    <li><strong>Sistem:</strong> Two-Tier Testing</li>
                                    <li><strong>Status:</strong> Menunggu guru memulai ujian</li>
                                </ul>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> 
                                        Zona waktu Anda: <strong id="user-timezone">{{ $session->timezone }}</strong>
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

@push('styles')
<style>
.avatar-lg {
    width: 4rem;
    height: 4rem;
}
.avatar-sm {
    width: 2rem;
    height: 2rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Display user timezone
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    $('#user-timezone').text(timezone);
    
    // Auto refresh to check for exam status changes
    setInterval(function() {
        // Check if exam status changed
        $.get(`/api/exam-status?exam_code={{ $exam->getCurrentCode() }}`, function(data) {
            if (data.success && data.exam.status === 'active') {
                // Exam started, reload the page
                location.reload();
            }
        }).fail(function() {
            // Handle error silently
            console.log('Failed to check exam status');
        });
    }, 10000); // Check every 10 seconds
    
    @if(!$session->student_name)
    // Focus on name input only if form is shown
    $('#student_name').focus();
    @endif
});
</script>
@endpush
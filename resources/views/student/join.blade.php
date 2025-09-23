@extends('layouts.student')

@section('title', 'Masuk Ujian')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-clipboard-check fa-3x text-primary mb-3"></i>
                            <h2 class="card-title">Masuk Ujian</h2>
                            <p class="text-muted">Masukkan kode ujian untuk bergabung</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('exam.join.submit') }}" id="joinForm">
                            @csrf
                            <input type="hidden" name="timezone" id="timezone">
                            <input type="hidden" name="platform" id="platform">
                            <input type="hidden" name="browser" id="browser">

                            <div class="mb-4">
                                <label for="exam_code" class="form-label">Kode Ujian</label>
                                <input type="text" 
                                       class="form-control form-control-lg text-center exam-code-input @error('exam_code') is-invalid @enderror" 
                                       id="exam_code" 
                                       name="exam_code" 
                                       value="{{ old('exam_code') }}"
                                       maxlength="6" 
                                       placeholder="Masukkan 6 digit kode"
                                       required>
                                @error('exam_code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Masuk Ujian
                                </button>
                            </div>
                        </form>

                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Pastikan koneksi internet stabil selama ujian
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Instructions Card -->
                <div class="card mt-4">
                    <div class="card-header text-center bg-light">
                        <h6 class="mb-0 text-muted">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Untuk Guru
                        </h6>
                    </div>
                    <div class="card-body text-center py-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Login Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.exam-code-input {
    letter-spacing: 0.3em;
    font-size: 1.5em;
}

@media (max-width: 768px) {
    .exam-code-input {
        letter-spacing: 0.1em !important;
        font-size: 1.0em !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-uppercase and format the exam code
    $('#exam_code').on('input', function() {
        let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (value.length > 6) {
            value = value.substring(0, 6);
        }
        this.value = value;
    });

    // Get device information
    detectDeviceInfo();
    
    // Focus on input
    $('#exam_code').focus();
});

function detectDeviceInfo() {
    // Get timezone
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    $('#timezone').val(timezone);
    
    // Get platform info
    const platform = navigator.platform;
    $('#platform').val(platform);
    
    // Get browser info (simplified)
    const userAgent = navigator.userAgent;
    let browser = 'Unknown';
    
    if (userAgent.indexOf('Chrome') > -1) {
        browser = 'Chrome';
    } else if (userAgent.indexOf('Firefox') > -1) {
        browser = 'Firefox';
    } else if (userAgent.indexOf('Safari') > -1) {
        browser = 'Safari';
    } else if (userAgent.indexOf('Edge') > -1) {
        browser = 'Edge';
    }
    
    $('#browser').val(browser);
}

// Prevent form resubmission
$('#joinForm').on('submit', function() {
    $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
});
</script>
@endpush
@extends('layouts.app')

@section('title', 'Ikut Ujian - Ujian Bahasa Arab')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-pencil-square text-primary" style="font-size: 3rem;"></i>
                        <h3 class="mt-3 mb-1">Ikut Ujian</h3>
                        <p class="text-muted">Masukkan kode ujian dan nama lengkap Anda</p>
                    </div>

                    <form method="POST" action="{{ route('exam.join.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="exam_code" class="form-label">Kode Ujian</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input id="exam_code" type="text" 
                                       class="form-control @error('exam_code') is-invalid @enderror" 
                                       name="exam_code" 
                                       value="{{ old('exam_code') }}" 
                                       required 
                                       autofocus
                                       maxlength="6"
                                       style="text-transform: uppercase;"
                                       placeholder="Contoh: ABC123">
                            </div>
                            @error('exam_code')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">Masukkan kode ujian yang diberikan oleh guru</small>
                        </div>

                        <div class="mb-3">
                            <label for="student_name" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input id="student_name" type="text" 
                                       class="form-control @error('student_name') is-invalid @enderror" 
                                       name="student_name" 
                                       value="{{ old('student_name') }}" 
                                       required
                                       placeholder="Masukkan nama lengkap Anda">
                            </div>
                            @error('student_name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_identifier" class="form-label">NIS/NISN (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-card-text"></i>
                                </span>
                                <input id="student_identifier" type="text" 
                                       class="form-control" 
                                       name="student_identifier" 
                                       value="{{ old('student_identifier') }}"
                                       placeholder="Masukkan NIS atau NISN (jika ada)">
                            </div>
                            <small class="text-muted">Opsional - untuk memudahkan identifikasi</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-arrow-right-circle"></i>
                                Masuk Ujian
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-2">Untuk guru/admin:</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login
                        </a>
                    </div>

                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i>
                            Pastikan koneksi internet stabil selama ujian
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    
    .card {
        border: none;
        border-radius: 1rem;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    
    .form-control {
        border-left: none;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    #exam_code {
        font-weight: bold;
        letter-spacing: 2px;
    }
</style>

<script>
    // Auto uppercase exam code
    document.getElementById('exam_code').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
</script>
@endsection

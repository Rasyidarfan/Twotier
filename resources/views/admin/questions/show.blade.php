@extends('layouts.app')

@section('title', 'Tampilkan Soal')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-eye me-2"></i>
                Tampilkan Soal
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Soal</a></li>
                    <li class="breadcrumb-item active">Tampilkan Soal</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>
                Kembali ke Daftar
            </a>
            <a href="{{ route('admin.questions.edit', $question->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>
                Edit Soal
            </a>
        </div>
    </div>

    <!-- Question Details -->
    <div class="row">
        <div class="col-12">
            <!-- Question Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>
                        Informasi Soal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mata Pelajaran:</strong> {{ $question->chapter->subject->name ?? 'Tidak Ditentukan' }}</p>
                            <p><strong>Bab:</strong> {{ $question->chapter->name ?? 'Tidak Ditentukan' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Nilai:</strong> {{ $question->points ?? 'Tidak Ditentukan' }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $question->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ $question->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </p>
                            <p><strong>Tanggal Dibuat:</strong> {{ $question->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    @if($question->creator)
                        <p><strong>Dibuat oleh:</strong> {{ $question->creator->name }}</p>
                    @endif
                </div>
            </div>

            <!-- Tier 1 Question -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-1-circle me-2"></i>
                        Soal Tingkat 1 (Pemahaman Konsep)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="question-content mb-4">
                        <h5>Isi Soal:</h5>
                        <div class="p-3 bg-light rounded" style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif; font-size: 1.1rem; line-height: 1.6;">
                            {{ $question->tier1_question }}
                        </div>
                    </div>

                    @if($question->tier1_image)
                        <div class="question-image mb-4">
                            <h6>Gambar Soal:</h6>
                            <img src="{{ asset('storage/' . $question->tier1_image) }}" 
                                 class="img-fluid rounded shadow" 
                                 style="max-height: 300px;" 
                                 alt="Gambar Soal">
                        </div>
                    @endif

                    <div class="question-options">
                        <h6>Pilihan Jawaban:</h6>
                        <div class="row">
                            @php
                                $arabicLetters = ['أ', 'ب', 'ج', 'د', 'ه']; // أ، ب، ج، د، ه
                                $tier1Options = is_array($question->tier1_options) ? $question->tier1_options : [];
                            @endphp
                            @for($i = 0; $i < 5; $i++)
                                @if(isset($tier1Options[$i]))
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" disabled
                                                   {{ $question->tier1_correct_answer === $i ? 'checked' : '' }}>
                                            <label class="form-check-label {{ $question->tier1_correct_answer === $i ? 'text-success fw-bold' : '' }}"
                                                   style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                                {{ $arabicLetters[$i] }}) {{ $tier1Options[$i] }}
                                            </label>
                                            @if($question->tier1_correct_answer === $i)
                                                <i class="bi bi-check-circle text-success ms-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tier 2 Question -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-2-circle me-2"></i>
                        Soal Tingkat 2 (Alasan/Pembenaran)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="question-content mb-4">
                        <h5>Isi Soal:</h5>
                        <div class="p-3 bg-light rounded" style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif; font-size: 1.1rem; line-height: 1.6;">
                            {{ $question->tier2_question }}
                        </div>
                    </div>

                    @if($question->tier2_image)
                        <div class="question-image mb-4">
                            <h6>Gambar Soal:</h6>
                            <img src="{{ asset('storage/' . $question->tier2_image) }}" 
                                 class="img-fluid rounded shadow" 
                                 style="max-height: 300px;" 
                                 alt="Gambar Soal">
                        </div>
                    @endif

                    <div class="question-options">
                        <h6>Pilihan Jawaban:</h6>
                        <div class="row">
                            @php
                                $arabicLetters = ['أ', 'ب', 'ج', 'د', 'ه']; // أ، ب، ج، د، ه
                                $tier2Options = is_array($question->tier2_options) ? $question->tier2_options : [];
                            @endphp
                            @for($i = 0; $i < 5; $i++)
                                @if(isset($tier2Options[$i]))
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" disabled
                                                   {{ $question->tier2_correct_answer === $i ? 'checked' : '' }}>
                                            <label class="form-check-label {{ $question->tier2_correct_answer === $i ? 'text-success fw-bold' : '' }}"
                                                   style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                                {{ $arabicLetters[$i] }}) {{ $tier2Options[$i] }}
                                            </label>
                                            @if($question->tier2_correct_answer === $i)
                                                <i class="bi bi-check-circle text-success ms-2"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics (if available) -->
            @if(isset($question->usage_count) && $question->usage_count > 0)
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="bi bi-graph-up me-2"></i>
                            Statistik Penggunaan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-info">{{ $question->usage_count }}</h4>
                                    <p class="mb-0">Jumlah Digunakan</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-success">{{ $question->correct_answers ?? 0 }}</h4>
                                    <p class="mb-0">Jawaban Benar</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-warning">{{ $question->success_rate ?? 0 }}%</h4>
                                    <p class="mb-0">Tingkat Keberhasilan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

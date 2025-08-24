@extends('layouts.app')

@section('title', 'Hasil Ujian - ' . $exam->title)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="card bg-primary text-white mb-4">
                <div class="card-body text-center">
                    <i class="bi bi-trophy-fill" style="font-size: 3rem;"></i>
                    <h2 class="mt-3 mb-1">Hasil Ujian</h2>
                    <h4>{{ $exam->title }}</h4>
                    <p class="mb-0">{{ $exam->subject->name }} - Kelas {{ $exam->grade }} {{ ucfirst($exam->semester) }}</p>
                </div>
            </div>

            <!-- Student Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="bi bi-person-circle"></i> Informasi Peserta</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ $session->student_name }}</td>
                                </tr>
                                @if($session->student_identifier)
                                <tr>
                                    <td><strong>NIS/NISN:</strong></td>
                                    <td>{{ $session->student_identifier }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Waktu Mulai:</strong></td>
                                    <td>{{ $session->started_at->format('d M Y, H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu Selesai:</strong></td>
                                    <td>{{ $session->finished_at->format('d M Y, H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="bi bi-clock-history"></i> Durasi Ujian</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Durasi Maksimal:</strong></td>
                                    <td>{{ $exam->duration_minutes }} menit</td>
                                </tr>
                                <tr>
                                    <td><strong>Durasi Aktual:</strong></td>
                                    <td>{{ $session->started_at->diffInMinutes($session->finished_at) }} menit</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($session->status === 'finished')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($session->status === 'timeout')
                                            <span class="badge bg-warning">Timeout</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Score Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart-fill"></i>
                        Ringkasan Nilai Two-Tier
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h2 class="mb-1">{{ $breakdown['benar-benar'] }}</h2>
                                    <p class="mb-0">Benar-Benar</p>
                                    <small>Konsep & Alasan Benar</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h2 class="mb-1">{{ $breakdown['benar-salah'] }}</h2>
                                    <p class="mb-0">Benar-Salah</p>
                                    <small>Konsep Benar, Alasan Salah</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h2 class="mb-1">{{ $breakdown['salah-benar'] }}</h2>
                                    <p class="mb-0">Salah-Benar</p>
                                    <small>Konsep Salah, Alasan Benar</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h2 class="mb-1">{{ $breakdown['salah-salah'] }}</h2>
                                    <p class="mb-0">Salah-Salah</p>
                                    <small>Konsep & Alasan Salah</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Score -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h1 class="display-4 mb-2">{{ $examResult->total_score }}</h1>
                                    <h4>Total Skor</h4>
                                    <p class="mb-0">dari {{ $answers->count() * 10 }} poin maksimal</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="progress" style="height: 30px;">
                                @php
                                    $maxScore = $answers->count() * 10;
                                    $percentage = $maxScore > 0 ? ($examResult->total_score / $maxScore) * 100 : 0;
                                @endphp
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     style="width: {{ $percentage }}%">
                                    {{ number_format($percentage, 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Results -->
            @if($exam->show_result_immediately)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-list-check"></i>
                        Detail Jawaban
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($answers as $index => $answer)
                        <div class="card mb-3 border-start border-4 
                            @switch($answer->result_category)
                                @case('benar-benar') border-success @break
                                @case('benar-salah') border-warning @break
                                @case('salah-benar') border-info @break
                                @case('salah-salah') border-danger @break
                            @endswitch">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="mb-0">Soal {{ $index + 1 }}</h6>
                                    <div>
                                        <span class="badge result-badge result-{{ $answer->result_category }}">
                                            {{ ucwords(str_replace('-', ' ', $answer->result_category)) }}
                                        </span>
                                        <span class="badge bg-primary ms-1">{{ $answer->points_earned }} poin</span>
                                    </div>
                                </div>

                                <!-- Tier 1 -->
                                <div class="mb-3">
                                    <h6 class="text-primary">
                                        <i class="bi bi-1-circle"></i>
                                        Pertanyaan Utama
                                    </h6>
                                    <div class="arabic-text bg-light p-2 rounded mb-2">
                                        {{ $answer->question->tier1_question }}
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Jawaban Anda:</strong>
                                            <div class="arabic-option {{ $answer->tier1_answer === $answer->question->tier1_correct_answer ? 'text-success' : 'text-danger' }}">
                                                {{ chr(65 + $answer->tier1_answer) }}. {{ $answer->question->tier1_options[$answer->tier1_answer] }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Jawaban Benar:</strong>
                                            <div class="arabic-option text-success">
                                                {{ chr(65 + $answer->question->tier1_correct_answer) }}. {{ $answer->question->tier1_options[$answer->question->tier1_correct_answer] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tier 2 -->
                                <div>
                                    <h6 class="text-success">
                                        <i class="bi bi-2-circle"></i>
                                        Alasan Pemilihan
                                    </h6>
                                    <div class="arabic-text bg-light p-2 rounded mb-2">
                                        {{ $answer->question->tier2_question }}
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Jawaban Anda:</strong>
                                            <div class="arabic-option {{ $answer->tier2_answer === $answer->question->tier2_correct_answer ? 'text-success' : 'text-danger' }}">
                                                {{ $answer->tier2_answer + 1 }}. {{ $answer->question->tier2_options[$answer->tier2_answer] }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Jawaban Benar:</strong>
                                            <div class="arabic-option text-success">
                                                {{ $answer->question->tier2_correct_answer + 1 }}. {{ $answer->question->tier2_options[$answer->question->tier2_correct_answer] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="card">
                <div class="card-body text-center">
                    <p class="text-muted mb-3">
                        <i class="bi bi-info-circle"></i>
                        Hasil ujian telah disimpan. Anda dapat menutup halaman ini.
                    </p>
                    
                    <div class="btn-group">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="bi bi-printer"></i>
                            Cetak Hasil
                        </button>
                        <a href="{{ route('exam.join') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Kembali ke Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn-group, .card:last-child {
            display: none !important;
        }
        
        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }
    }
    
    .border-start {
        border-left-width: 4px !important;
    }
</style>
@endsection

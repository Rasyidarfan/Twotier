@extends('layouts.student')

@section('title', 'Hasil Ujian - ' . $exam->title)

@php
    $arabicLetters = ['أ', 'ب', 'ج', 'د', 'ه'];
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="card result-header text-white mb-4">
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
                                    <h2 class="mb-1">{{ $breakdown['benar_benar'] }}</h2>
                                    <p class="mb-0">Benar-Benar</p>
                                    <small>Konsep & Alasan Benar</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h2 class="mb-1">{{ $breakdown['benar_salah'] }}</h2>
                                    <p class="mb-0">Benar-Salah</p>
                                    <small>Konsep Benar, Alasan Salah</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h2 class="mb-1">{{ $breakdown['salah_benar'] }}</h2>
                                    <p class="mb-0">Salah-Benar</p>
                                    <small>Konsep Salah, Alasan Benar</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h2 class="mb-1">{{ $breakdown['salah_salah'] }}</h2>
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
                        <div class="card mb-3 border-start border-4 border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="mb-0">السؤال {{ $index + 1 }}</h6>
                                </div>

                                <!-- Tier 1 -->
                                <div class="mb-3">
                                    <h6 class="text-primary">
                                        <i class="bi bi-1-circle"></i>
                                        المستوى الأول
                                    </h6>
                                    <div class="question-text-rtl bg-light p-3 rounded mb-2">
                                        {!! $answer->question->tier1_question !!}
                                    </div>
                                    <div class="answer-section">
                                        <strong>إجابتك:</strong>
                                        <div class="student-answer-box p-2 mt-2 rounded">
                                            <span class="option-letter">{{ $arabicLetters[$answer->tier1_answer] ?? chr(65 + $answer->tier1_answer) }}</span>
                                            <span class="option-text-rtl">{{ $answer->question->tier1_options[$answer->tier1_answer] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tier 2 -->
                                <div>
                                    <h6 class="text-success">
                                        <i class="bi bi-2-circle"></i>
                                        المستوى الثاني
                                    </h6>
                                    <div class="question-text-rtl bg-light p-3 rounded mb-2">
                                        {!! $answer->question->tier2_question !!}
                                    </div>
                                    <div class="answer-section">
                                        <strong>إجابتك:</strong>
                                        <div class="student-answer-box p-2 mt-2 rounded">
                                            <span class="option-letter">{{ $arabicLetters[$answer->tier2_answer] ?? chr(65 + $answer->tier2_answer) }}</span>
                                            <span class="option-text-rtl">{{ $answer->question->tier2_options[$answer->tier2_answer] }}</span>
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

@push('styles')
<style>
    /* Result Header with Gradient */
    .result-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    /* RTL Styling for Arabic Text */
    .question-text-rtl {
        direction: rtl;
        text-align: right;
        font-family: 'Amiri', 'Noto Sans Arabic', serif;
        font-size: 1.1em;
        line-height: 1.8;
    }

    .option-text-rtl {
        direction: rtl;
        text-align: right;
        font-family: 'Amiri', 'Noto Sans Arabic', serif;
        font-size: 1em;
        line-height: 1.6;
    }

    .answer-section {
        direction: rtl;
        text-align: right;
    }

    .student-answer-box {
        background-color: #f8f9fa;
        border: 2px solid #dee2e6;
        direction: rtl;
        text-align: right;
    }

    .option-letter {
        display: inline-block;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #6777ef;
        color: white;
        text-align: center;
        line-height: 30px;
        font-weight: 600;
        margin-left: 10px;
    }

    .border-start {
        border-left-width: 4px !important;
    }

    .border-primary {
        border-color: #6777ef !important;
    }

    /* Print Styles */
    @media print {
        body {
            background-color: white !important;
        }

        .container {
            max-width: 100% !important;
        }

        .btn-group,
        .card:last-child,
        button {
            display: none !important;
        }

        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
            page-break-inside: avoid;
            margin-bottom: 1rem !important;
        }

        .result-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .card-body {
            padding: 1rem !important;
        }

        h1, h2, h3, h4, h5, h6 {
            page-break-after: avoid;
        }

        .question-text-rtl,
        .option-text-rtl {
            font-size: 12pt !important;
            line-height: 1.6 !important;
        }

        .student-answer-box {
            border: 1px solid #000 !important;
            padding: 0.5rem !important;
        }

        .option-letter {
            background-color: #6777ef !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Preserve colors for result categories */
        .bg-success,
        .border-success {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-warning,
        .border-warning {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-info,
        .border-info {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-danger,
        .border-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-primary {
            background-color: #6777ef !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .border-primary {
            border-color: #6777ef !important;
        }

        .badge {
            border: 1px solid #000;
        }

        /* Score Summary Cards - Use thick borders as alternative to background */
        .row.text-center .col-md-3 .card.bg-success,
        .card.bg-success {
            border: 5px solid #28a745 !important;
            background-color: #f8f9fa !important;
        }

        .card.bg-success h2 {
            color: #28a745 !important;
            font-weight: bold !important;
        }

        .card.bg-success p,
        .card.bg-success small {
            color: #333 !important;
        }

        .row.text-center .col-md-3 .card.bg-warning,
        .card.bg-warning {
            border: 5px solid #ffc107 !important;
            background-color: #f8f9fa !important;
        }

        .card.bg-warning h2 {
            color: #ffc107 !important;
            font-weight: bold !important;
        }

        .card.bg-warning p,
        .card.bg-warning small {
            color: #333 !important;
        }

        .row.text-center .col-md-3 .card.bg-info,
        .card.bg-info {
            border: 5px solid #17a2b8 !important;
            background-color: #f8f9fa !important;
        }

        .card.bg-info h2 {
            color: #17a2b8 !important;
            font-weight: bold !important;
        }

        .card.bg-info p,
        .card.bg-info small {
            color: #333 !important;
        }

        .row.text-center .col-md-3 .card.bg-danger,
        .card.bg-danger {
            border: 5px solid #dc3545 !important;
            background-color: #f8f9fa !important;
        }

        .card.bg-danger h2 {
            color: #dc3545 !important;
            font-weight: bold !important;
        }

        .card.bg-danger p,
        .card.bg-danger small {
            color: #333 !important;
        }

        .row .col-12 .card.bg-primary,
        .card.bg-primary {
            border: 5px solid #6777ef !important;
            background-color: #f8f9fa !important;
        }

        .card.bg-primary h1,
        .card.bg-primary .display-4 {
            color: #6777ef !important;
            font-weight: bold !important;
        }

        .card.bg-primary h4,
        .card.bg-primary p {
            color: #333 !important;
        }

        /* Page breaks */
        .card.mb-3 {
            page-break-inside: avoid;
        }

        .card.mb-4 {
            page-break-inside: avoid;
        }
    }

    @page {
        margin: 1cm;
        size: A4;
    }
</style>
@endpush
@endsection

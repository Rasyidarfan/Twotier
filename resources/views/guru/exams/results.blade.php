@extends('layouts.app')

@section('title', 'Hasil Ujian')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-graph-up me-2"></i>
                Hasil Ujian
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.exams.index') }}">Ujian</a></li>
                    <li class="breadcrumb-item active">Hasil</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>
                Kembali ke Ujian
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-2"></i>
                    Ekspor Hasil
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('guru.exams.export', ['exam' => $exam->id, 'format' => 'excel']) }}">
                        <i class="bi bi-file-earmark-excel me-2"></i>Excel
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('guru.exams.export', ['exam' => $exam->id, 'format' => 'pdf']) }}">
                        <i class="bi bi-file-earmark-pdf me-2"></i>PDF
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('guru.exams.export', ['exam' => $exam->id, 'format' => 'csv']) }}">
                        <i class="bi bi-file-earmark-text me-2"></i>CSV
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Exam Information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-info-circle me-2"></i>
                Informasi Ujian
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="text-primary">{{ $exam->title }}</h5>
                    <p class="text-muted mb-2">{{ $exam->description }}</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-book me-2 text-info"></i>
                                <span>{{ $exam->subject->name ?? 'Tidak Ditentukan' }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock me-2 text-warning"></i>
                                <span>{{ $exam->duration_minutes }} menit</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-question-circle me-2 text-success"></i>
                                <span>{{ $exam->examQuestions->count() }} soal</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar me-2 text-primary"></i>
                                <span>{{ $exam->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h3 class="text-primary">{{ $exam->code }}</h3>
                        <p class="text-muted">Kode Ujian</p>
                        <span class="badge bg-{{ $exam->status == 'completed' ? 'success' : 'secondary' }} fs-6">
                            {{ $exam->status == 'completed' ? 'Selesai' : $exam->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Peserta
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalParticipants }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tingkat Kelulusan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($passRate, 1) }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Nilai Rata-rata
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($averageScore, 1) }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Nilai Tertinggi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($highestScore, 1) }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-trophy-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-bar-chart me-2"></i>
                        Distribusi Nilai
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="scoresChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pie-chart me-2"></i>
                        Persentase Lulus & Gagal
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="passFailChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Analysis -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-question-circle me-2"></i>
                Analisis Soal
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th>No. Soal</th>
                        <th>Soal</th>
                        <th>Tingkat Kesulitan</th>
                        <th>Jawaban Benar</th>
                        <th>Jawaban Salah</th>
                        <th>Tingkat Keberhasilan</th>
                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questionAnalysis as $index => $analysis)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;" title="{{ $analysis['question']->tier1_question }}">
                                        {{ Str::limit($analysis['question']->tier1_question, 80) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $analysis['question']->difficulty == 'easy' ? 'success' : ($analysis['question']->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                        {{ $analysis['question']->difficulty == 'easy' ? 'Mudah' : ($analysis['question']->difficulty == 'medium' ? 'Sedang' : 'Sulit') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $analysis['correct_answers'] }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">{{ $analysis['wrong_answers'] }}</span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-{{ $analysis['success_rate'] >= 70 ? 'success' : ($analysis['success_rate'] >= 50 ? 'warning' : 'danger') }}" 
                                             role="progressbar" style="width: {{ $analysis['success_rate'] }}%">
                                            {{ number_format($analysis['success_rate'], 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" 
                                            onclick="viewQuestionDetails({{ $analysis['question']->id }})" title="Detail Soal">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-2"></i>
                Hasil Peserta
            </h6>
            <div>
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="filterResults('all')">Semua Hasil</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterResults('passed')">Yang Lulus</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterResults('failed')">Yang Tidak Lulus</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterResults('completed')">Yang Selesai</a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-sm btn-info" onclick="refreshResults()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="resultsTable">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Durasi</th>
                            <th>Nilai</th>
                            <th>Persentase</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="resultsTableBody">
                        @foreach($results as $result)
                            <tr class="result-row" data-status="{{ $result->status }}" data-passed="{{ $result->percentage >= $exam->passing_score ? 'true' : 'false' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $result->user->name }}</h6>
                                            <small class="text-muted">{{ $result->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $result->started_at ? $result->started_at->format('Y-m-d H:i:s') : '-' }}</td>
                                <td>{{ $result->completed_at ? $result->completed_at->format('Y-m-d H:i:s') : '-' }}</td>
                                <td>
                                    @if($result->started_at && $result->completed_at)
                                        {{ $result->started_at->diffInMinutes($result->completed_at) }} menit
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info fs-6">
                                        {{ $result->score }}/{{ $result->total_score }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 100px; height: 20px;">
                                            <div class="progress-bar bg-{{ $result->percentage >= $exam->passing_score ? 'success' : 'danger' }}" 
                                                 role="progressbar" style="width: {{ $result->percentage }}%">
                                            </div>
                                        </div>
                                        <span class="text-{{ $result->percentage >= $exam->passing_score ? 'success' : 'danger' }}">
                                            {{ number_format($result->percentage, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($result->status == 'completed')
                                        @if($result->percentage >= 60)
                                            <span class="badge bg-success">Lulus</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Lulus</span>
                                        @endif
                                    @else
                                        <span class="badge bg-warning">{{ $result->status == 'in_progress' ? 'Sedang Berjalan' : 'Belum Selesai' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info" 
                                                onclick="viewDetailedResult({{ $result->id }})" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                onclick="downloadResultPDF({{ $result->id }})" title="Download PDF">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </button>
                                        @if($result->status == 'completed')
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="sendResultEmail({{ $result->id }})" title="Kirim Hasil via Email">
                                                <i class="bi bi-envelope"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeScoresChart();
    initializePassFailChart();
});

function initializeScoresChart() {
    const ctx = document.getElementById('scoresChart').getContext('2d');
    const scoresData = @json($scoresDistribution);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['0-20%', '21-40%', '41-60%', '61-80%', '81-100%'],
            datasets: [{
                label: 'Jumlah Siswa',
                data: scoresData,
                backgroundColor: [
                    'rgba(220, 53, 69, 0.8)',
                    'rgba(253, 126, 20, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(25, 135, 84, 0.8)',
                    'rgba(13, 110, 253, 0.8)'
                ],
                borderColor: [
                    'rgba(220, 53, 69, 1)',
                    'rgba(253, 126, 20, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(25, 135, 84, 1)',
                    'rgba(13, 110, 253, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

function initializePassFailChart() {
    const ctx = document.getElementById('passFailChart').getContext('2d');
    const passedCount = {{ $passedCount }};
    const failedCount = {{ $failedCount }};
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Lulus', 'Tidak Lulus'],
            datasets: [{
                data: [passedCount, failedCount],
                backgroundColor: [
                    'rgba(25, 135, 84, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgba(25, 135, 84, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function filterResults(filter) {
    const rows = document.querySelectorAll('.result-row');
    
    rows.forEach(row => {
        let show = true;
        
        switch(filter) {
            case 'passed':
                show = row.dataset.passed === 'true' && row.dataset.status === 'completed';
                break;
            case 'failed':
                show = row.dataset.passed === 'false' && row.dataset.status === 'completed';
                break;
            case 'completed':
                show = row.dataset.status === 'completed';
                break;
            case 'all':
            default:
                show = true;
                break;
        }
        
        row.style.display = show ? '' : 'none';
    });
}

function refreshResults() {
    location.reload();
}

function viewDetailedResult(resultId) {
    window.open(`/guru/exam-results/${resultId}/details`, '_blank');
}

function downloadResultPDF(resultId) {
    window.open(`/guru/exam-results/${resultId}/pdf`, '_blank');
}

function sendResultEmail(resultId) {
    Swal.fire({
        title: 'Kirim Hasil via Email',
        text: 'Apakah Anda ingin mengirim hasil ujian kepada siswa melalui email?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Kirim',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/guru/exam-results/${resultId}/send-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Hasil ujian berhasil dikirim melalui email',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat mengirim email',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function viewQuestionDetails(questionId) {
    fetch(`/guru/questions/${questionId}/analysis/{{ $exam->id }}`)
        .then(response => response.json())
        .then(data => {
            let html = `
                <div class="text-start">
                    <h6>Soal Utama:</h6>
                    <p>${data.question.tier1_question}</p>
                    <h6>Pilihan Jawaban:</h6>
                    <ul>
                        <li class="${data.question.tier1_correct_answer === 'a' ? 'text-success fw-bold' : ''}">A) ${data.question.tier1_option_a}</li>
                        <li class="${data.question.tier1_correct_answer === 'b' ? 'text-success fw-bold' : ''}">B) ${data.question.tier1_option_b}</li>
                        <li class="${data.question.tier1_correct_answer === 'c' ? 'text-success fw-bold' : ''}">C) ${data.question.tier1_option_c}</li>
                        <li class="${data.question.tier1_correct_answer === 'd' ? 'text-success fw-bold' : ''}">D) ${data.question.tier1_option_d}</li>
                    </ul>
                    <h6>Soal Penjelasan:</h6>
                    <p>${data.question.tier2_question}</p>
                    <h6>Pilihan Penjelasan:</h6>
                    <ul>
                        <li class="${data.question.tier2_correct_answer === 'a' ? 'text-success fw-bold' : ''}">A) ${data.question.tier2_option_a}</li>
                        <li class="${data.question.tier2_correct_answer === 'b' ? 'text-success fw-bold' : ''}">B) ${data.question.tier2_option_b}</li>
                        <li class="${data.question.tier2_correct_answer === 'c' ? 'text-success fw-bold' : ''}">C) ${data.question.tier2_option_c}</li>
                        <li class="${data.question.tier2_correct_answer === 'd' ? 'text-success fw-bold' : ''}">D) ${data.question.tier2_option_d}</li>
                    </ul>
                    <hr>
                    <h6>Statistik Jawaban:</h6>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Level 1:</small>
                            <ul class="list-unstyled">
                                <li>A: ${data.tier1_stats.a || 0} jawaban</li>
                                <li>B: ${data.tier1_stats.b || 0} jawaban</li>
                                <li>C: ${data.tier1_stats.c || 0} jawaban</li>
                                <li>D: ${data.tier1_stats.d || 0} jawaban</li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Level 2:</small>
                            <ul class="list-unstyled">
                                <li>A: ${data.tier2_stats.a || 0} jawaban</li>
                                <li>B: ${data.tier2_stats.b || 0} jawaban</li>
                                <li>C: ${data.tier2_stats.c || 0} jawaban</li>
                                <li>D: ${data.tier2_stats.d || 0} jawaban</li>
                            </ul>
                        </div>
                    </div>
                </div>
            `;
            
            Swal.fire({
                title: 'Analisis Soal',
                html: html,
                width: '800px',
                confirmButtonText: 'Tutup'
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat analisis soal',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
}
</script>
@endpush
@endsection

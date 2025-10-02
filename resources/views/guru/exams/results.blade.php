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

    <!-- Answer Category Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pie-chart me-2"></i>
                        Kategori Jawaban Siswa
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="answerCategoryChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-bar-chart-steps me-2"></i>
                        Analisis Jawaban per Bab
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="chapterBreakdownChart" width="400" height="200"></canvas>
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
                            <th>No.</th>
                            <th>Soal</th>
                            <th>Benar-Benar</th>
                            <th>Benar-Salah</th>
                            <th>Salah-Benar</th>
                            <th>Salah-Salah</th>
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
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $analysis['benar_benar'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark">{{ $analysis['benar_salah'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $analysis['salah_benar'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $analysis['salah_salah'] }}</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info"
                                            onclick="viewQuestionDetails({{ $analysis['question']->id }})" title="Lihat Detail">
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
                                <td class="text-center">
                                    <a href="{{ route('guru.exams.student-result', ['exam' => $exam->id, 'session' => $result->id]) }}"
                                       class="btn btn-sm btn-info"
                                       target="_blank"
                                       title="Lihat Detail">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
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
    initializeAnswerCategoryChart();
    initializeChapterBreakdownChart();
});

function initializeScoresChart() {
    const ctx = document.getElementById('scoresChart').getContext('2d');
    const scoresData = @json($scoresDistribution);

    // Create smooth density curve data points
    const densityData = [];
    const smoothness = 100; // Number of points for smooth curve

    // Create interpolated points for smooth curve
    for (let i = 0; i <= smoothness; i++) {
        const x = (i / smoothness) * 100; // 0 to 100%
        let y = 0;

        // Gaussian kernel density estimation
        scoresData.forEach((count, index) => {
            const binCenter = (index * 20) + 10; // Center of each bin (10, 30, 50, 70, 90)
            const bandwidth = 15; // Smoothing parameter
            const distance = (x - binCenter) / bandwidth;
            y += count * Math.exp(-0.5 * distance * distance);
        });

        densityData.push({x: x, y: y});
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                label: 'Density',
                data: densityData,
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return 'Nilai: ' + context[0].parsed.x.toFixed(1) + '%';
                        },
                        label: function(context) {
                            return 'Density: ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    type: 'linear',
                    min: 0,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Nilai (%)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Kepadatan'
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

function initializeAnswerCategoryChart() {
    const ctx = document.getElementById('answerCategoryChart').getContext('2d');
    const categoryData = @json($answerCategoryBreakdown);

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Benar-Benar', 'Benar-Salah', 'Salah-Benar', 'Salah-Salah'],
            datasets: [{
                data: [
                    categoryData.benar_benar,
                    categoryData.benar_salah,
                    categoryData.salah_benar,
                    categoryData.salah_salah
                ],
                backgroundColor: [
                    'rgba(25, 135, 84, 0.8)',   // Green for benar-benar
                    'rgba(255, 193, 7, 0.8)',   // Yellow for benar-salah
                    'rgba(13, 110, 253, 0.8)',  // Blue for salah-benar
                    'rgba(220, 53, 69, 0.8)'    // Red for salah-salah
                ],
                borderColor: [
                    'rgba(25, 135, 84, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(13, 110, 253, 1)',
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}

function initializeChapterBreakdownChart() {
    const ctx = document.getElementById('chapterBreakdownChart').getContext('2d');
    const chapterData = @json($chapterBreakdown);

    const labels = chapterData.map(item => item.chapter_name);

    // Calculate percentages for each chapter
    const benarBenarData = chapterData.map(item => {
        const total = item.benar_benar + item.benar_salah + item.salah_benar + item.salah_salah;
        return total > 0 ? ((item.benar_benar / total) * 100) : 0;
    });
    const benarSalahData = chapterData.map(item => {
        const total = item.benar_benar + item.benar_salah + item.salah_benar + item.salah_salah;
        return total > 0 ? ((item.benar_salah / total) * 100) : 0;
    });
    const salahBenarData = chapterData.map(item => {
        const total = item.benar_benar + item.benar_salah + item.salah_benar + item.salah_salah;
        return total > 0 ? ((item.salah_benar / total) * 100) : 0;
    });
    const salahSalahData = chapterData.map(item => {
        const total = item.benar_benar + item.benar_salah + item.salah_benar + item.salah_salah;
        return total > 0 ? ((item.salah_salah / total) * 100) : 0;
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Benar-Benar',
                    data: benarBenarData,
                    backgroundColor: 'rgba(25, 135, 84, 0.8)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    rawData: chapterData.map(item => item.benar_benar)
                },
                {
                    label: 'Benar-Salah',
                    data: benarSalahData,
                    backgroundColor: 'rgba(255, 193, 7, 0.8)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1,
                    rawData: chapterData.map(item => item.benar_salah)
                },
                {
                    label: 'Salah-Benar',
                    data: salahBenarData,
                    backgroundColor: 'rgba(13, 110, 253, 0.8)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    rawData: chapterData.map(item => item.salah_benar)
                },
                {
                    label: 'Salah-Salah',
                    data: salahSalahData,
                    backgroundColor: 'rgba(220, 53, 69, 0.8)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    rawData: chapterData.map(item => item.salah_salah)
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const percentage = context.parsed.x || 0;
                            const rawValue = context.dataset.rawData[context.dataIndex];
                            return label + ': ' + rawValue + ' (' + percentage.toFixed(1) + '%)';
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                y: {
                    stacked: true
                }
            }
        }
    });

    // Adjust canvas height based on number of chapters
    const canvas = document.getElementById('chapterBreakdownChart');
    canvas.style.height = (labels.length * 50 + 100) + 'px';
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
    fetch(`/guru/exams/{{ $exam->id }}/questions/${questionId}/analysis`)
        .then(response => response.json())
        .then(data => {
            const q = data.question;

            // Ensure options are objects, not strings
            const tier1Opts = typeof q.tier1_options === 'string' ? JSON.parse(q.tier1_options) : q.tier1_options;
            const tier2Opts = typeof q.tier2_options === 'string' ? JSON.parse(q.tier2_options) : q.tier2_options;
            const arabicLetters = ['أ', 'ب', 'ج', 'د', 'ه'];

            // Helper function to escape HTML
            const escapeHtml = (text) => {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            };

            try {
                let html = `
                <div class="modal-question-analysis">
                    <div class="mb-3 text-center">
                        <span class="badge bg-info me-2">${escapeHtml(q.subject || 'N/A')}</span>
                        <span class="badge bg-secondary">${escapeHtml(q.chapter || 'N/A')}</span>
                    </div>

                    <!-- Tier 1 Question -->
                    <div class="tier-section-modal mb-4">
                        <div class="tier-header-modal bg-primary text-white p-3 rounded-top">
                            <h6 class="mb-0">المستوى الأول</h6>
                        </div>
                        <div class="tier-content-modal border rounded-bottom p-3">
                            <div class="question-text-modal mb-3">
                                ${q.tier1_question || ''}
                            </div>
                            <div class="options-modal">
                                ${Object.keys(tier1Opts).map((key, index) => {
                                    const correctAnswer = q.tier1_correct_answer ? String(q.tier1_correct_answer).toLowerCase() : '';
                                    const isCorrect = correctAnswer === key.toLowerCase();
                                    const percentage = data.tier1_percentages && data.tier1_percentages[key] !== undefined ? data.tier1_percentages[key] : 0;
                                    return `
                                        <div class="option-modal ${isCorrect ? 'correct-answer' : ''}" style="background: linear-gradient(to right, #7bed9f ${percentage}%, #efefef ${percentage}%);">
                                            <div class="option-label-modal">
                                                <div class="option-circle-modal ${isCorrect ? 'correct' : ''}">
                                                    ${isCorrect ? '<i class="bi bi-key-fill"></i>' : arabicLetters[index]}
                                                </div>
                                            </div>
                                            <div class="option-text-modal">
                                                ${tier1Opts[key]}
                                            </div>
                                            <div class="option-percentage-modal">
                                                <span class="badge ${isCorrect ? 'bg-success' : 'bg-dark'}">${percentage}%</span>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>

                    <!-- Tier 2 Question -->
                    <div class="tier-section-modal mb-4">
                        <div class="tier-header-modal bg-success text-white p-3 rounded-top">
                            <h6 class="mb-0">المستوى الثاني</h6>
                        </div>
                        <div class="tier-content-modal border rounded-bottom p-3">
                            <div class="question-text-modal mb-3">
                                ${q.tier2_question || ''}
                            </div>
                            <div class="options-modal">
                                ${Object.keys(tier2Opts).map((key, index) => {
                                    const correctAnswer = q.tier2_correct_answer ? String(q.tier2_correct_answer).toLowerCase() : '';
                                    const isCorrect = correctAnswer === key.toLowerCase();
                                    const percentage = data.tier2_percentages && data.tier2_percentages[key] !== undefined ? data.tier2_percentages[key] : 0;
                                    return `
                                        <div class="option-modal ${isCorrect ? 'correct-answer' : ''}" style="background: linear-gradient(to right, #7bed9f ${percentage}%, #efefef ${percentage}%);">
                                            <div class="option-label-modal">
                                                <div class="option-circle-modal ${isCorrect ? 'correct' : ''}">
                                                    ${isCorrect ? '<i class="bi bi-key-fill"></i>' : arabicLetters[index]}
                                                </div>
                                            </div>
                                            <div class="option-text-modal">
                                                ${tier2Opts[key]}
                                            </div>
                                            <div class="option-percentage-modal">
                                                <span class="badge ${isCorrect ? 'bg-success' : 'bg-dark'}">${percentage}%</span>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-primary mb-3 text-center">إحصائيات الإجابات (${data.total_answers} طالب)</h6>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-center mb-2">فئات الإجابات</h6>
                            <div style="height: 80px;">
                                <canvas id="modalCategoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .modal-question-analysis {
                        direction: rtl;
                        text-align: right;
                    }

                    .tier-section-modal {
                        border: 1px solid #dee2e6;
                        border-radius: 0.375rem;
                        overflow: hidden;
                    }

                    .tier-header-modal {
                        margin: 0;
                        direction: rtl;
                        text-align: right;
                    }

                    .tier-content-modal {
                        border-top: none !important;
                        background-color: #ffffff;
                        direction: rtl;
                        text-align: right;
                    }

                    .question-text-modal {
                        direction: rtl;
                        text-align: right;
                        font-family: 'Amiri', 'Noto Sans Arabic', serif;
                        font-size: 1.1rem;
                        line-height: 1.8;
                    }

                    .options-modal {
                        display: grid;
                        grid-template-columns: 1fr;
                        gap: 10px;
                    }

                    .option-modal {
                        display: flex;
                        align-items: center;
                        padding: 12px;
                        border: 2px solid #e1e5eb;
                        border-radius: 8px;
                        direction: rtl;
                        text-align: right;
                        transition: all 0.2s ease;
                        position: relative;
                        overflow: hidden;
                    }

                    .option-modal.correct-answer {
                        border: 3px solid #28a745;
                        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
                        outline: 2px dashed #28a745;
                        outline-offset: 3px;
                    }

                    .option-label-modal {
                        display: flex;
                        align-items: center;
                        margin-left: 10px;
                    }

                    .option-circle-modal {
                        width: 38px;
                        height: 38px;
                        border-radius: 50%;
                        background-color: #f1f1f1;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-weight: 600;
                        font-size: 1.1em;
                        margin-left: 10px;
                        transition: all 0.3s ease;
                    }

                    .option-circle-modal.correct {
                        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                        color: white;
                        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
                        font-size: 1.2em;
                        animation: pulse-key 2s ease-in-out infinite;
                    }

                    @keyframes pulse-key {
                        0%, 100% {
                            transform: scale(1);
                            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
                        }
                        50% {
                            transform: scale(1.05);
                            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.6);
                        }
                    }

                    .option-text-modal {
                        flex: 1;
                        text-align: right;
                        direction: rtl;
                        font-family: 'Amiri', 'Noto Sans Arabic', serif;
                        font-size: 1rem;
                        line-height: 1.6;
                    }

                    .option-percentage-modal {
                        margin-right: 10px;
                    }
                </style>
            `;

                Swal.fire({
                    title: 'Detail Analisis Soal',
                    html: html,
                    width: '900px',
                    confirmButtonText: 'Tutup',
                    didOpen: () => {
                        // Initialize chart in modal
                        initModalCategoryChart(data.category_breakdown, data.total_answers);
                    }
                });
            } catch (renderError) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error saat render modal: ' + renderError.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
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

function initModalCategoryChart(categoryData, totalAnswers) {
    const ctx = document.getElementById('modalCategoryChart').getContext('2d');

    // Calculate percentages
    const benarBenarPct = totalAnswers > 0 ? ((categoryData.benar_benar / totalAnswers) * 100) : 0;
    const benarSalahPct = totalAnswers > 0 ? ((categoryData.benar_salah / totalAnswers) * 100) : 0;
    const salahBenarPct = totalAnswers > 0 ? ((categoryData.salah_benar / totalAnswers) * 100) : 0;
    const salahSalahPct = totalAnswers > 0 ? ((categoryData.salah_salah / totalAnswers) * 100) : 0;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [''],
            datasets: [
                {
                    label: 'Benar-Benar',
                    data: [benarBenarPct],
                    backgroundColor: 'rgba(25, 135, 84, 0.9)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    rawData: categoryData.benar_benar
                },
                {
                    label: 'Benar-Salah',
                    data: [benarSalahPct],
                    backgroundColor: 'rgba(255, 193, 7, 0.9)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1,
                    rawData: categoryData.benar_salah
                },
                {
                    label: 'Salah-Benar',
                    data: [salahBenarPct],
                    backgroundColor: 'rgba(13, 110, 253, 0.9)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    rawData: categoryData.salah_benar
                },
                {
                    label: 'Salah-Salah',
                    data: [salahSalahPct],
                    backgroundColor: 'rgba(220, 53, 69, 0.9)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    rawData: categoryData.salah_salah
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 15,
                        font: {
                            size: 11
                        },
                        padding: 10
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const percentage = context.parsed.x || 0;
                            const rawValue = context.dataset.rawData;
                            return label + ': ' + rawValue + ' (' + percentage.toFixed(1) + '%)';
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        },
                        font: {
                            size: 10
                        }
                    }
                },
                y: {
                    stacked: true,
                    display: false
                }
            }
        }
    });
}

</script>
@endpush
@endsection

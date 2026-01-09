@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Analisis Butir Soal</h2>
                        <p class="text-muted mb-0">{{ $exam->title }}</p>
                    </div>
                    <a href="{{ route('guru.exams.results', $exam->id) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        @if($analysis['statistics']['total_students'] < 10)
            <div class="alert alert-warning" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Peringatan:</strong> Jumlah siswa kurang dari 10 ({{ $analysis['statistics']['total_students'] }}
                siswa). Hasil analisis mungkin kurang akurat.
            </div>
        @endif

        <!-- Reliability Summary Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ringkasan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="mb-2">
                                    <h6 class="text-muted">Koefisien Alpha (α)</h6>
                                    <h2 class="mb-0 text-{{ $analysis['reliability']['color'] }}">
                                        {{ number_format($analysis['reliability']['alpha'], 3) }}
                                    </h2>
                                    <span class="badge bg-{{ $analysis['reliability']['color'] }} mt-2">
                                        {{ $analysis['reliability']['interpretation'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-2">
                                    <h6 class="text-muted">Jumlah Siswa</h6>
                                    <h3 class="mb-0">{{ $analysis['statistics']['total_students'] }}</h3>
                                    @php
                                        $df = $analysis['statistics']['total_students'] - 2;
                                        $rTabel = $analysis['items'][array_key_first($analysis['items'])]['validity']['r_tabel'] ?? 0;
                                    @endphp
                                    <small class="text-muted d-block mt-1">r tabel = {{ number_format($rTabel, 3) }}
                                        (df={{ $df }})</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-2">
                                    <h6 class="text-muted">Jumlah Soal</h6>
                                    <h3 class="mb-0">{{ $analysis['statistics']['total_questions'] }}</h3>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-2">
                                    <h6 class="text-muted">Rata-rata Validitas</h6>
                                    <h3 class="mb-0">{{ number_format($analysis['statistics']['avg_validity'], 3) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Rata-rata Daya Beda</h6>
                        <h2 class="mb-0">{{ number_format($analysis['statistics']['avg_discrimination'], 3) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Rata-rata Tingkat Kesukaran</h6>
                        <h2 class="mb-0">{{ number_format($analysis['statistics']['avg_difficulty'], 3) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Analysis Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Analisis Butir Soal Detail</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle" id="itemAnalysisTable">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th style="width: 8%;">No</th>
                                        <th style="width: 35%;">Soal</th>
                                        <th style="width: 12%;">Tingkat Kesukaran (P)</th>
                                        <th style="width: 12%;">Status P</th>
                                        <th style="width: 12%;">Daya Beda (D)</th>
                                        <th style="width: 12%;">Status D</th>
                                        <th style="width: 9%;">r hitung</th>
                                        <th style="width: 12%;">Status Validitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analysis['items'] as $item)
                                        <tr>
                                            <td class="text-center">{{ $item['question_number'] }}</td>
                                            <td>
                                                <small>{{ Str::limit($item['question_text'], 150) }}</small>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($item['difficulty']['value'], 3) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $item['difficulty']['color'] }}">
                                                    {{ $item['difficulty']['status'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($item['discrimination']['value'], 3) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $item['discrimination']['color'] }}">
                                                    {{ $item['discrimination']['status'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($item['validity']['r_hitung'], 3) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $item['validity']['color'] }}">
                                                    {{ $item['validity']['status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Scores Matrix Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Tabel Nilai Siswa (Skala 0-2)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle" id="studentScoresTable">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 20%;">Nama Siswa</th>
                                        @foreach($analysis['items'] as $item)
                                            <th style="width: {{ 60 / count($analysis['items']) }}%;">
                                                S{{ $item['question_number'] }}</th>
                                        @endforeach
                                        <th style="width: 15%;">Total Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analysis['student_scores'] as $index => $student)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $student['student_name'] }}</td>
                                            @foreach($student['scores'] as $score)
                                                <td class="text-center">
                                                    @if($score == 2)
                                                        <span class="badge bg-success">{{ $score }}</span>
                                                    @elseif($score == 1)
                                                        <span class="badge bg-warning text-dark">{{ $score }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ $score }}</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-center"><strong>{{ $student['total_score'] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <strong>Keterangan:</strong>
                                <span class="badge bg-success">2</span> = Benar-Benar (Paham Konsep),
                                <span class="badge bg-warning text-dark">1</span> = Parsial (Benar-Salah atau Salah-Benar),
                                <span class="badge bg-danger">0</span> = Salah-Salah (Tidak Paham)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visualization Charts -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Distribusi Tingkat Kesukaran</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="difficultyChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Distribusi Daya Beda</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="discriminationChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Distribusi Validitas</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="validityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Legend and Interpretation Guide -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Panduan Interpretasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="text-primary">Tingkat Kesukaran (P)</h6>
                                <ul class="list-unstyled">
                                    <li><span class="badge bg-info">Mudah</span> P > 0.70</li>
                                    <li><span class="badge bg-success">Sedang</span> 0.30 ≤ P ≤ 0.70</li>
                                    <li><span class="badge bg-warning">Sukar</span> P < 0.30</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-primary">Daya Beda (D)</h6>
                                <ul class="list-unstyled">
                                    <li><span class="badge bg-success">Sangat Baik</span> D > 0.70</li>
                                    <li><span class="badge bg-success">Baik</span> 0.40 < D ≤ 0.70</li>
                                    <li><span class="badge bg-warning">Sedang</span> 0.20 < D ≤ 0.40</li>
                                    <li><span class="badge bg-danger">Jelek</span> 0.00 < D ≤ 0.20</li>
                                    <li><span class="badge bg-danger">Sangat Jelek</span> D ≤ 0.00</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-primary">Validitas</h6>
                                <ul class="list-unstyled">
                                    <li><span class="badge bg-success">Valid</span> r hitung > r tabel</li>
                                    <li><span class="badge bg-danger">Tidak Valid</span> r hitung ≤ r tabel</li>
                                </ul>
                                <h6 class="text-primary mt-3">Reliabilitas</h6>
                                <ul class="list-unstyled">
                                    <li><span class="badge bg-success">Reliabel</span> α > 0.6</li>
                                    <li><span class="badge bg-danger">Tidak Reliabel</span> α ≤ 0.6</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Prepare data for charts
                const items = @json($analysis['items']);

                // Count distribution for difficulty
                const difficultyDistribution = {
                    'Mudah': 0,
                    'Sedang': 0,
                    'Sukar': 0
                };

                // Count distribution for discrimination
                const discriminationDistribution = {
                    'Sangat Baik': 0,
                    'Baik': 0,
                    'Sedang': 0,
                    'Jelek': 0,
                    'Sangat Jelek': 0
                };

                // Count distribution for validity
                const validityDistribution = {
                    'Valid': 0,
                    'Tidak Valid': 0
                };

                Object.values(items).forEach(item => {
                    // Difficulty
                    if (item.difficulty.value > 0.70) {
                        difficultyDistribution['Mudah']++;
                    } else if (item.difficulty.value >= 0.30) {
                        difficultyDistribution['Sedang']++;
                    } else {
                        difficultyDistribution['Sukar']++;
                    }

                    // Discrimination
                    if (item.discrimination.value > 0.70) {
                        discriminationDistribution['Sangat Baik']++;
                    } else if (item.discrimination.value > 0.40) {
                        discriminationDistribution['Baik']++;
                    } else if (item.discrimination.value > 0.20) {
                        discriminationDistribution['Sedang']++;
                    } else if (item.discrimination.value > 0.00) {
                        discriminationDistribution['Jelek']++;
                    } else {
                        discriminationDistribution['Sangat Jelek']++;
                    }

                    // Validity
                    if (item.validity.is_valid) {
                        validityDistribution['Valid']++;
                    } else {
                        validityDistribution['Tidak Valid']++;
                    }
                });

                // Difficulty Chart
                const difficultyCtx = document.getElementById('difficultyChart').getContext('2d');
                new Chart(difficultyCtx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(difficultyDistribution),
                        datasets: [{
                            data: Object.values(difficultyDistribution),
                            backgroundColor: [
                                'rgba(13, 202, 240, 0.7)',  // info
                                'rgba(25, 135, 84, 0.7)',   // success
                                'rgba(255, 193, 7, 0.7)'    // warning
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Discrimination Chart
                const discriminationCtx = document.getElementById('discriminationChart').getContext('2d');
                new Chart(discriminationCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(discriminationDistribution),
                        datasets: [{
                            label: 'Jumlah Soal',
                            data: Object.values(discriminationDistribution),
                            backgroundColor: [
                                'rgba(25, 135, 84, 0.7)',   // success
                                'rgba(25, 135, 84, 0.7)',   // success
                                'rgba(255, 193, 7, 0.7)',   // warning
                                'rgba(220, 53, 69, 0.7)',   // danger
                                'rgba(220, 53, 69, 0.7)'    // danger
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // Validity Chart
                const validityCtx = document.getElementById('validityChart').getContext('2d');
                new Chart(validityCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(validityDistribution),
                        datasets: [{
                            data: Object.values(validityDistribution),
                            backgroundColor: [
                                'rgba(25, 135, 84, 0.7)',   // success
                                'rgba(220, 53, 69, 0.7)'    // danger
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
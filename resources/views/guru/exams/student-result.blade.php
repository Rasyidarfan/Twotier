@extends('layouts.app')

@section('title', 'Detail Hasil Peserta')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-person-badge me-2"></i>
                Detail Hasil Peserta
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.exams.index') }}">Ujian</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.exams.results', $exam->id) }}">Hasil</a></li>
                    <li class="breadcrumb-item active">Detail Peserta</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('guru.exams.results', $exam->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Kembali ke Hasil Ujian
            </a>
        </div>
    </div>

    <!-- Student Information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-person-circle me-2"></i>
                Informasi Peserta
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold" style="width: 150px;">Nama</td>
                            <td>: {{ $session->student_name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Identitas</td>
                            <td>: {{ $session->student_identifier ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Waktu Mulai</td>
                            <td>: {{ $session->started_at ? $session->started_at->format('Y-m-d H:i:s') : '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold" style="width: 150px;">Ujian</td>
                            <td>: {{ $exam->title }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Nilai</td>
                            <td>: <span class="badge bg-primary fs-6">{{ $session->total_score ?? 0 }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status</td>
                            <td>:
                                @if($session->status === 'finished')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($session->status === 'timeout')
                                    <span class="badge bg-warning">Timeout</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($session->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Category Breakdown -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-bar-chart me-2"></i>
                Kategori Jawaban Keseluruhan
            </h6>
        </div>
        <div class="card-body">
            <canvas id="overallCategoryChart" style="height: 100px;"></canvas>
        </div>
    </div>

    <!-- Chapter-based Category Breakdown -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-book me-2"></i>
                Kategori Jawaban per Bab
            </h6>
        </div>
        <div class="card-body">
            <canvas id="chapterCategoryChart" style="height: {{ count($chapterBreakdown) * 60 }}px;"></canvas>
        </div>
    </div>

    <!-- Question Details -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-list-ol me-2"></i>
                Detail Soal dan Jawaban
            </h6>
        </div>
        <div class="card-body">
            @foreach($questionDetails as $detail)
            <div class="card mb-3 border-{{ $detail['answer'] && $detail['answer']->result_category === 'benar-benar' ? 'success' : ($detail['answer'] && $detail['answer']->result_category === 'salah-salah' ? 'danger' : 'warning') }}">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <span class="badge bg-primary me-2">Soal {{ $detail['number'] }}</span>
                            <small class="text-muted">{{ $detail['chapter'] }}</small>
                        </h6>
                        @if($detail['answer'])
                            @if($detail['answer']->result_category === 'benar-benar')
                                <span class="badge bg-success">Paham Konsep</span>
                            @elseif($detail['answer']->result_category === 'benar-salah')
                                <span class="badge bg-warning">Miskonsepsi</span>
                            @elseif($detail['answer']->result_category === 'salah-benar')
                                <span class="badge bg-info">Menebak</span>
                            @else
                                <span class="badge bg-danger">Tidak Paham Konsep</span>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Tier 1 -->
                        <div class="col-md-6 mb-3">
                            <div class="tier-section border-end pe-3">
                                <div class="tier-header bg-primary text-white p-2 rounded mb-2">
                                    <h6 class="mb-0">المستوى الأول (Tier 1)</h6>
                                </div>
                                <div class="tier-question mb-3">
                                    <p class="fw-bold text-end" dir="rtl">{{ $detail['question']->tier1_question }}</p>
                                </div>
                                <div class="tier-options">
                                    @php
                                        $tier1Options = is_array($detail['question']->tier1_options)
                                            ? $detail['question']->tier1_options
                                            : json_decode($detail['question']->tier1_options, true);
                                        $arabicLetters = ['أ', 'ب', 'ج', 'د', 'ه'];
                                    @endphp
                                    @foreach($tier1Options as $index => $option)
                                    @php
                                        // Convert to integer index for reliable comparison
                                        $optionIndex = is_numeric($index) ? (int)$index : array_search($index, array_keys($tier1Options));

                                        $correctAnswerIndex = null;
                                        if ($detail['question']->tier1_correct_answer !== null) {
                                            $correctKey = $detail['question']->tier1_correct_answer;
                                            $correctAnswerIndex = is_numeric($correctKey) ? (int)$correctKey : array_search($correctKey, array_keys($tier1Options));
                                        }

                                        $studentAnswerIndex = null;
                                        if ($detail['answer'] && $detail['answer']->tier1_answer !== null) {
                                            $studentKey = $detail['answer']->tier1_answer;
                                            $studentAnswerIndex = is_numeric($studentKey) ? (int)$studentKey : array_search($studentKey, array_keys($tier1Options));
                                        }

                                        $isCorrectAnswer = $correctAnswerIndex !== null && $correctAnswerIndex === $optionIndex;
                                        $isStudentAnswer = $studentAnswerIndex !== null && $studentAnswerIndex === $optionIndex;
                                    @endphp
                                    <div class="option-item mb-2 p-2 rounded
                                        @if($isCorrectAnswer)
                                            border border-success bg-success bg-opacity-10
                                        @elseif($isStudentAnswer && !$isCorrectAnswer)
                                            border border-danger bg-danger bg-opacity-10
                                        @else
                                            border
                                        @endif">
                                        <div class="d-flex align-items-center justify-content-end" dir="rtl">
                                            <span class="text-end flex-grow-1">{{ $option }}</span>
                                            <span class="badge ms-2
                                                @if($isCorrectAnswer)
                                                    bg-success
                                                @elseif($isStudentAnswer && !$isCorrectAnswer)
                                                    bg-danger
                                                @else
                                                    bg-secondary
                                                @endif">
                                                @if($isCorrectAnswer)
                                                    <i class="bi bi-key-fill"></i>
                                                @elseif($isStudentAnswer && !$isCorrectAnswer)
                                                    <i class="bi bi-x-circle-fill"></i>
                                                @else
                                                    {{ $arabicLetters[$optionIndex] ?? $index }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Tier 2 -->
                        <div class="col-md-6 mb-3">
                            <div class="tier-section ps-3">
                                <div class="tier-header bg-success text-white p-2 rounded mb-2">
                                    <h6 class="mb-0">المستوى الثاني (Tier 2)</h6>
                                </div>
                                <div class="tier-question mb-3">
                                    <p class="fw-bold text-end" dir="rtl">{{ $detail['question']->tier2_question }}</p>
                                </div>
                                <div class="tier-options">
                                    @php
                                        $tier2Options = is_array($detail['question']->tier2_options)
                                            ? $detail['question']->tier2_options
                                            : json_decode($detail['question']->tier2_options, true);
                                    @endphp
                                    @foreach($tier2Options as $index => $option)
                                    @php
                                        // Convert to integer index for reliable comparison
                                        $optionIndex = is_numeric($index) ? (int)$index : array_search($index, array_keys($tier2Options));

                                        $correctAnswerIndex = null;
                                        if ($detail['question']->tier2_correct_answer !== null) {
                                            $correctKey = $detail['question']->tier2_correct_answer;
                                            $correctAnswerIndex = is_numeric($correctKey) ? (int)$correctKey : array_search($correctKey, array_keys($tier2Options));
                                        }

                                        $studentAnswerIndex = null;
                                        if ($detail['answer'] && $detail['answer']->tier2_answer !== null) {
                                            $studentKey = $detail['answer']->tier2_answer;
                                            $studentAnswerIndex = is_numeric($studentKey) ? (int)$studentKey : array_search($studentKey, array_keys($tier2Options));
                                        }

                                        $isCorrectAnswer = $correctAnswerIndex !== null && $correctAnswerIndex === $optionIndex;
                                        $isStudentAnswer = $studentAnswerIndex !== null && $studentAnswerIndex === $optionIndex;
                                    @endphp
                                    <div class="option-item mb-2 p-2 rounded
                                        @if($isCorrectAnswer)
                                            border border-success bg-success bg-opacity-10
                                        @elseif($isStudentAnswer && !$isCorrectAnswer)
                                            border border-danger bg-danger bg-opacity-10
                                        @else
                                            border
                                        @endif">
                                        <div class="d-flex align-items-center justify-content-end" dir="rtl">
                                            <span class="text-end flex-grow-1">{{ $option }}</span>
                                            <span class="badge ms-2
                                                @if($isCorrectAnswer)
                                                    bg-success
                                                @elseif($isStudentAnswer && !$isCorrectAnswer)
                                                    bg-danger
                                                @else
                                                    bg-secondary
                                                @endif">
                                                @if($isCorrectAnswer)
                                                    <i class="bi bi-key-fill"></i>
                                                @elseif($isStudentAnswer && !$isCorrectAnswer)
                                                    <i class="bi bi-x-circle-fill"></i>
                                                @else
                                                    {{ $arabicLetters[$optionIndex] ?? $index }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($detail['answer'])
                    <div class="mt-3 p-2 bg-light rounded">
                        <small class="text-muted">
                            <strong>Poin:</strong> {{ $detail['answer']->points_earned ?? 0 }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Overall Category Chart
    const overallData = @json($overallBreakdown);
    const totalOverall = overallData.benar_benar + overallData.benar_salah + overallData.salah_benar + overallData.salah_salah;

    const overallPercentages = {
        benar_benar: totalOverall > 0 ? ((overallData.benar_benar / totalOverall) * 100).toFixed(1) : 0,
        benar_salah: totalOverall > 0 ? ((overallData.benar_salah / totalOverall) * 100).toFixed(1) : 0,
        salah_benar: totalOverall > 0 ? ((overallData.salah_benar / totalOverall) * 100).toFixed(1) : 0,
        salah_salah: totalOverall > 0 ? ((overallData.salah_salah / totalOverall) * 100).toFixed(1) : 0
    };

    new Chart(document.getElementById('overallCategoryChart'), {
        type: 'bar',
        data: {
            labels: ['Kategori'],
            datasets: [
                {
                    label: 'Paham Konsep',
                    data: [parseFloat(overallPercentages.benar_benar)],
                    backgroundColor: 'rgba(25, 135, 84, 0.8)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    rawData: [overallData.benar_benar]
                },
                {
                    label: 'Miskonsepsi',
                    data: [parseFloat(overallPercentages.benar_salah)],
                    backgroundColor: 'rgba(255, 193, 7, 0.8)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1,
                    rawData: [overallData.benar_salah]
                },
                {
                    label: 'Menebak',
                    data: [parseFloat(overallPercentages.salah_benar)],
                    backgroundColor: 'rgba(13, 110, 253, 0.8)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    rawData: [overallData.salah_benar]
                },
                {
                    label: 'Tidak Paham Konsep',
                    data: [parseFloat(overallPercentages.salah_salah)],
                    backgroundColor: 'rgba(220, 53, 69, 0.8)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    rawData: [overallData.salah_salah]
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
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

    // Chapter Category Chart
    const chapterData = @json($chapterBreakdown);

    const chapterLabels = chapterData.map(ch => ch.chapter_name);
    const chapterDatasets = [
        {
            label: 'Paham Konsep',
            data: chapterData.map(ch => {
                const total = ch.benar_benar + ch.benar_salah + ch.salah_benar + ch.salah_salah;
                return total > 0 ? ((ch.benar_benar / total) * 100).toFixed(1) : 0;
            }),
            backgroundColor: 'rgba(25, 135, 84, 0.8)',
            borderColor: 'rgba(25, 135, 84, 1)',
            borderWidth: 1,
            rawData: chapterData.map(ch => ch.benar_benar)
        },
        {
            label: 'Miskonsepsi',
            data: chapterData.map(ch => {
                const total = ch.benar_benar + ch.benar_salah + ch.salah_benar + ch.salah_salah;
                return total > 0 ? ((ch.benar_salah / total) * 100).toFixed(1) : 0;
            }),
            backgroundColor: 'rgba(255, 193, 7, 0.8)',
            borderColor: 'rgba(255, 193, 7, 1)',
            borderWidth: 1,
            rawData: chapterData.map(ch => ch.benar_salah)
        },
        {
            label: 'Menebak',
            data: chapterData.map(ch => {
                const total = ch.benar_benar + ch.benar_salah + ch.salah_benar + ch.salah_salah;
                return total > 0 ? ((ch.salah_benar / total) * 100).toFixed(1) : 0;
            }),
            backgroundColor: 'rgba(13, 110, 253, 0.8)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 1,
            rawData: chapterData.map(ch => ch.salah_benar)
        },
        {
            label: 'Tidak Paham Konsep',
            data: chapterData.map(ch => {
                const total = ch.benar_benar + ch.benar_salah + ch.salah_benar + ch.salah_salah;
                return total > 0 ? ((ch.salah_salah / total) * 100).toFixed(1) : 0;
            }),
            backgroundColor: 'rgba(220, 53, 69, 0.8)',
            borderColor: 'rgba(220, 53, 69, 1)',
            borderWidth: 1,
            rawData: chapterData.map(ch => ch.salah_salah)
        }
    ];

    new Chart(document.getElementById('chapterCategoryChart'), {
        type: 'bar',
        data: {
            labels: chapterLabels,
            datasets: chapterDatasets
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
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
});
</script>
@endpush

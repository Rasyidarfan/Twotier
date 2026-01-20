@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Edit Jawaban Siswa</h2>
                    <p class="text-muted mb-0">Session ID: {{ $session->id }}</p>
                </div>
                <a href="{{ route('admin.edit-student-answer') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Session Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-muted">Nama Siswa</h6>
                            <p class="mb-0"><strong>{{ $session->student_name }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Ujian</h6>
                            <p class="mb-0"><strong>{{ $session->exam->title }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Total Skor Saat Ini</h6>
                            <p class="mb-0"><strong id="currentTotalScore">{{ $session->total_score }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Total Skor Preview</h6>
                            <p class="mb-0"><strong id="previewTotalScore" class="text-primary">{{ $session->total_score }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.update-student-answer', $session->id) }}" method="POST" id="editAnswersForm">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Edit Jawaban</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 25%">Soal</th>
                                        <th style="width: 15%">Tier 1</th>
                                        <th style="width: 15%">Tier 2</th>
                                        <th style="width: 15%">Kategori</th>
                                        <th style="width: 10%">Poin</th>
                                        <th style="width: 15%">Jawaban Benar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($answersData as $data)
                                    <tr class="answer-row"
                                        data-answer-id="{{ $data['answer_id'] }}"
                                        data-base-points="{{ $data['base_points'] }}"
                                        data-tier1-correct="{{ $data['tier1_correct'] }}"
                                        data-tier2-correct="{{ $data['tier2_correct'] }}">
                                        <td class="text-center">{{ $data['question_order'] }}</td>
                                        <td>
                                            <small>{{ Str::limit($data['question_text'], 100) }}</small>
                                        </td>
                                        <td>
                                            <select name="answers[{{ $data['answer_id'] }}][tier1]"
                                                    class="form-select form-select-sm tier1-select"
                                                    required>
                                                <option value="">-- Pilih --</option>
                                                @foreach($data['tier1_options'] as $index => $option)
                                                    <option value="{{ $index }}"
                                                            {{ $data['tier1_answer'] == $index ? 'selected' : '' }}>
                                                        {{ chr(65 + $index) }}. {{ Str::limit($option, 30) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="answers[{{ $data['answer_id'] }}][tier2]"
                                                    class="form-select form-select-sm tier2-select"
                                                    required>
                                                <option value="">-- Pilih --</option>
                                                @foreach($data['tier2_options'] as $index => $option)
                                                    <option value="{{ $index }}"
                                                            {{ $data['tier2_answer'] == $index ? 'selected' : '' }}>
                                                        {{ chr(65 + $index) }}. {{ Str::limit($option, 30) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge category-badge">{{ $data['result_category'] ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <strong class="points-display">{{ $data['points_earned'] }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <small>
                                                T1: <strong>{{ chr(65 + $data['tier1_correct']) }}</strong><br>
                                                T2: <strong>{{ chr(65 + $data['tier2_correct']) }}</strong>
                                            </small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="5" class="text-end"><strong>TOTAL:</strong></td>
                                        <td class="text-center">
                                            <strong id="footerTotalScore">{{ $session->total_score }}</strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg" onclick="window.location.reload()">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Recalculate All Scores Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <h5 class="mb-0 text-warning">
                        <i class="bi bi-calculator"></i> Hitung Ulang Semua Skor
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        <strong>Fitur ini akan menghitung ulang SEMUA skor siswa di SEMUA ujian</strong> berdasarkan jawaban yang tersimpan.
                    </p>
                    <p class="mb-3">Proses ini akan:</p>
                    <ul class="mb-3">
                        <li>Menghitung ulang <code>result_category</code> untuk setiap jawaban</li>
                        <li>Menghitung ulang <code>points_earned</code> berdasarkan kategori</li>
                        <li>Menghitung ulang <code>total_score</code> untuk setiap session</li>
                        <li>Update <code>scoring_breakdown</code> (benar-benar, benar-salah, dll)</li>
                        <li>Clear cache analisis butir soal</li>
                    </ul>
                    <p class="text-muted mb-3">
                        <i class="bi bi-info-circle"></i> Gunakan fitur ini jika ada perubahan pada sistem penilaian atau jika ada data yang tidak konsisten.
                    </p>
                    <form action="{{ route('admin.recalculate-all-scores') }}" method="POST" id="recalculateFormIndividual" class="d-inline">
                        @csrf
                        <button type="button" class="btn btn-warning" onclick="confirmRecalculateIndividual()">
                            <i class="bi bi-calculator"></i> Hitung Ulang Semua Skor
                        </button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.answer-row');

    // Function to calculate category based on answers
    function calculateCategory(tier1Answer, tier2Answer, tier1Correct, tier2Correct) {
        const tier1IsCorrect = parseInt(tier1Answer) === parseInt(tier1Correct);
        const tier2IsCorrect = parseInt(tier2Answer) === parseInt(tier2Correct);

        if (tier1IsCorrect && tier2IsCorrect) return 'benar-benar';
        if (tier1IsCorrect && !tier2IsCorrect) return 'benar-salah';
        if (!tier1IsCorrect && tier2IsCorrect) return 'salah-benar';
        return 'salah-salah';
    }

    // Function to calculate points based on category
    function calculatePoints(category, basePoints) {
        switch(category) {
            case 'benar-benar': return basePoints;
            case 'benar-salah': return Math.ceil(basePoints / 2);
            case 'salah-benar': return Math.ceil(basePoints / 2);
            case 'salah-salah': return 0;
            default: return 0;
        }
    }

    // Function to get badge class based on category
    function getCategoryBadgeClass(category) {
        switch(category) {
            case 'benar-benar': return 'bg-success';
            case 'benar-salah': return 'bg-warning text-dark';
            case 'salah-benar': return 'bg-info';
            case 'salah-salah': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }

    // Function to update total score
    function updateTotalScore() {
        let total = 0;
        rows.forEach(row => {
            const points = parseInt(row.querySelector('.points-display').textContent) || 0;
            total += points;
        });

        document.getElementById('previewTotalScore').textContent = total;
        document.getElementById('footerTotalScore').textContent = total;

        // Highlight if changed
        const currentScore = parseInt(document.getElementById('currentTotalScore').textContent);
        const previewElement = document.getElementById('previewTotalScore');
        if (total !== currentScore) {
            previewElement.classList.add('text-warning');
            previewElement.classList.remove('text-primary');
        } else {
            previewElement.classList.remove('text-warning');
            previewElement.classList.add('text-primary');
        }
    }

    // Function to update row when answers change
    function updateRow(row) {
        const tier1Select = row.querySelector('.tier1-select');
        const tier2Select = row.querySelector('.tier2-select');
        const categoryBadge = row.querySelector('.category-badge');
        const pointsDisplay = row.querySelector('.points-display');

        const tier1Answer = tier1Select.value;
        const tier2Answer = tier2Select.value;

        if (tier1Answer === '' || tier2Answer === '') {
            categoryBadge.textContent = '-';
            categoryBadge.className = 'badge bg-secondary';
            pointsDisplay.textContent = '0';
            updateTotalScore();
            return;
        }

        const tier1Correct = row.dataset.tier1Correct;
        const tier2Correct = row.dataset.tier2Correct;
        const basePoints = parseInt(row.dataset.basePoints);

        const category = calculateCategory(tier1Answer, tier2Answer, tier1Correct, tier2Correct);
        const points = calculatePoints(category, basePoints);

        categoryBadge.textContent = category;
        categoryBadge.className = 'badge ' + getCategoryBadgeClass(category);
        pointsDisplay.textContent = points;

        updateTotalScore();
    }

    // Attach event listeners
    rows.forEach(row => {
        const tier1Select = row.querySelector('.tier1-select');
        const tier2Select = row.querySelector('.tier2-select');

        tier1Select.addEventListener('change', () => updateRow(row));
        tier2Select.addEventListener('change', () => updateRow(row));
    });

    // Confirm before submit
    document.getElementById('editAnswersForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const currentScore = parseInt(document.getElementById('currentTotalScore').textContent);
        const newScore = parseInt(document.getElementById('previewTotalScore').textContent);

        if (confirm(`Apakah Anda yakin ingin menyimpan perubahan?\n\nSkor lama: ${currentScore}\nSkor baru: ${newScore}`)) {
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

            this.submit();
        }
    });
});

function confirmRecalculateIndividual() {
    if (confirm('PERINGATAN!\n\nFitur ini akan menghitung ulang SEMUA skor siswa di SEMUA ujian berdasarkan jawaban yang tersimpan (tier1_answer dan tier2_answer).\n\nProses ini akan:\n✓ Menghitung ulang result_category\n✓ Menghitung ulang points_earned\n✓ Menghitung ulang total_score per session\n✓ Update scoring_breakdown\n✓ Clear cache analisis butir soal\n\nApakah Anda yakin ingin melanjutkan?')) {
        const form = document.getElementById('recalculateFormIndividual');
        const submitBtn = form.querySelector('button');

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghitung ulang...';

        form.submit();
    }
}
</script>
@endpush
@endsection

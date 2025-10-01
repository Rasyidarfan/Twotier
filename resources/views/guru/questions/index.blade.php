@extends('layouts.app')

@section('title', 'Kelola Soal')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-question-circle me-2"></i>
                Kelola Soal
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Soal</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-funnel me-2"></i>
                Pencarian & Filter
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('guru.questions.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Cari dalam teks soal...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran</label>
                        <select class="form-select" id="subject_id" name="subject_id">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="chapter_id" class="form-label">Bab</label>
                        <select class="form-select" id="chapter_id" name="chapter_id">
                            <option value="">Semua Bab</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->id }}" {{ request('chapter_id') == $chapter->id ? 'selected' : '' }}>
                                    {{ $chapter->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Questions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-2"></i>
                Daftar Soal
            </h6>
        </div>
        <div class="card-body">
            @if($questions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="questionsTable">
                        <thead>
                            <tr>
                                <th>Soal</th>
                                <th>Mata Pelajaran/Bab</th>
                                <th>Poin</th>
                                <th>Penggunaan</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                                <tr>
                                    <td>
                                        <div class="question-preview">
                                            <div class="tier1-preview mb-2">
                                                <strong class="text-primary">Tingkat Pertama:</strong>
                                                <div class="text-truncate" style="max-width: 300px;" title="{{ $question->tier1_question }}">
                                                    {{ Str::limit($question->tier1_question, 80) }}
                                                </div>
                                            </div>
                                            <div class="tier2-preview">
                                                <strong class="text-success">Tingkat Kedua:</strong>
                                                <div class="text-truncate" style="max-width: 300px;" title="{{ $question->tier2_question }}">
                                                    {{ Str::limit($question->tier2_question, 80) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-info mb-1">{{ $question->chapter->subject->name ?? 'Tidak Ditentukan' }}</span>
                                            <br>
                                            <small class="text-muted">{{ $question->chapter->name ?? 'Tidak Ditentukan' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $question->points ?? 10 }}</span>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <small class="text-muted d-block">{{ $question->usage_count ?? 0 }} kali</small>
                                            @if($question->usage_count > 0)
                                                <div class="progress mt-1" style="height: 4px;">
                                                    <div class="progress-bar bg-info" style="width: {{ min(($question->usage_count / 10) * 100, 100) }}%"></div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $question->created_at->format('Y-m-d') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info"
                                                    onclick="previewQuestion({{ $question->id }})" title="Pratinjau">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                    onclick="viewQuestionStats({{ $question->id }})" title="Lihat Statistik">
                                                <i class="bi bi-graph-up"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $questions->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-question-circle fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">Tidak Ada Soal</h5>
                    <p class="text-gray-500">Tidak ditemukan soal yang sesuai dengan kriteria yang ditentukan.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Question Preview Modal -->
<div class="modal fade" id="questionPreviewModal" tabindex="-1" aria-labelledby="questionPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionPreviewModalLabel">
                    <i class="bi bi-eye me-2"></i>
                    Pratinjau Soal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="questionPreviewContent">
                <!-- Question preview will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Question Stats Modal -->
<div class="modal fade" id="questionStatsModal" tabindex="-1" aria-labelledby="questionStatsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionStatsModalLabel">
                    <i class="bi bi-graph-up me-2"></i>
                    Statistik Soal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="questionStatsContent">
                <!-- Question stats will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Subject change handler to load chapters
    const subjectSelect = document.getElementById('subject_id');
    const chapterSelect = document.getElementById('chapter_id');
    
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        const currentChapterId = '{{ request("chapter_id") }}';
        
        // Clear chapters
        chapterSelect.innerHTML = '<option value="">Semua Bab</option>';
        
        if (subjectId) {
            fetch(`/guru/subjects/${subjectId}/chapters`)
                .then(response => response.json())
                .then(chapters => {
                    chapters.forEach(chapter => {
                        const option = document.createElement('option');
                        option.value = chapter.id;
                        option.textContent = chapter.name;
                        if (chapter.id == currentChapterId) {
                            option.selected = true;
                        }
                        chapterSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading chapters:', error);
                });
        }
    });
    
    // Load chapters on page load if subject is selected
    if (subjectSelect.value) {
        subjectSelect.dispatchEvent(new Event('change'));
    }
});

function previewQuestion(questionId) {
    fetch(`/guru/questions/${questionId}`)
        .then(response => response.json())
        .then(question => {
            // Parse tier1_options and tier2_options from JSON
            const tier1Options = typeof question.tier1_options === 'string'
                ? JSON.parse(question.tier1_options)
                : question.tier1_options;
            const tier2Options = typeof question.tier2_options === 'string'
                ? JSON.parse(question.tier2_options)
                : question.tier2_options;

            // Arabic option letters: أ، ب، ج، د، ه
            const arabicLetters = ['أ', 'ب', 'ج', 'د', 'ه'];

            const content = `
                <div class="question-preview-content">
                    <!-- Tier 1 Question -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                        <i class="bi bi-1-circle me-2"></i>
                        Soal Utama (Tingkat Pertama)
                        </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-3" dir="rtl" style="text-align: right;">${question.tier1_question}</p>
                            <div class="row">
                                ${tier1Options.map((option, index) => `
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check" dir="rtl" style="text-align: right;">
                                            <label class="form-check-label ${question.tier1_correct_answer === index ? 'text-success fw-bold' : ''}" style="display: block; text-align: right;">
                                                ${arabicLetters[index]}) ${option}
                                            </label>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>

                    <!-- Tier 2 Question -->
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                        <i class="bi bi-2-circle me-2"></i>
                        Soal Alasan (Tingkat Kedua)
                        </h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-3" dir="rtl" style="text-align: right;">${question.tier2_question}</p>
                            <div class="row">
                                ${tier2Options.map((option, index) => `
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check" dir="rtl" style="text-align: right;">
                                            <label class="form-check-label ${question.tier2_correct_answer === index ? 'text-success fw-bold' : ''}" style="display: block; text-align: right;">
                                                ${arabicLetters[index]}) ${option}
                                            </label>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>

                    <!-- Question Info -->
                    <div class="card">
                        <div class="card-header">
                        <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Informasi Soal
                        </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Mata Pelajaran:</strong> ${question.subject?.name || 'Tidak Ditentukan'}</p>
                                    <p><strong>Bab:</strong> ${question.chapter?.name || 'Tidak Ditentukan'}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Poin:</strong> ${question.points}</p>
                                    <p><strong>Tanggal Dibuat:</strong> ${new Date(question.created_at).toLocaleDateString('id-ID')}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('questionPreviewContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('questionPreviewModal')).show();
        })
        .catch(error => {
            console.error('Error loading question:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat soal',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
}

function viewQuestionStats(questionId) {
    fetch(`/guru/questions/${questionId}/stats`)
        .then(response => response.json())
        .then(stats => {
            const content = `
                <div class="question-stats-content">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="bi bi-eye fa-2x text-primary mb-2"></i>
                                    <h4 class="text-primary">${stats.total_views || 0}</h4>
                                    <p class="mb-0">Kali Dilihat</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="bi bi-clipboard-check fa-2x text-success mb-2"></i>
                                    <h4 class="text-success">${stats.total_attempts || 0}</h4>
                                    <p class="mb-0">Kali Dijawab</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="bi bi-check-circle fa-2x text-info mb-2"></i>
                                    <h4 class="text-info">${stats.correct_answers || 0}</h4>
                                    <p class="mb-0">Jawaban Benar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="bi bi-percent fa-2x text-warning mb-2"></i>
                                    <h4 class="text-warning">${stats.success_rate || 0}%</h4>
                                    <p class="mb-0">Tingkat Keberhasilan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Penggunaan dalam Ujian</h6>
                                </div>
                                <div class="card-body">
                                    ${stats.exams && stats.exams.length > 0 ? 
                                        stats.exams.map(exam => `
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>${exam.title}</span>
                                                <small class="text-muted">${new Date(exam.created_at).toLocaleDateString('id-ID')}</small>
                                            </div>
                                        `).join('') : 
                                        '<p class="text-muted">Soal ini belum digunakan dalam ujian apapun</p>'
                                    }
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Performa Siswa</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Tingkat Pertama</label>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" style="width: ${stats.tier1_success_rate || 0}%">
                                                ${stats.tier1_success_rate || 0}%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tingkat Kedua</label>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: ${stats.tier2_success_rate || 0}%">
                                                ${stats.tier2_success_rate || 0}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('questionStatsContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('questionStatsModal')).show();
        })
        .catch(error => {
            console.error('Error loading question stats:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat statistik soal',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
}

</script>
@endpush
@endsection

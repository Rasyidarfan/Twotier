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
        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus me-2"></i>
            Tambah Soal Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Soal
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $questions->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-question-circle-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Soal Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeQuestions }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
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
                    <div class="col-md-2 mb-3">
                        <label for="difficulty" class="form-label">Tingkat Kesulitan</label>
                        <select class="form-select" id="difficulty" name="difficulty">
                            <option value="">Semua Tingkat</option>
                            <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Mudah</option>
                            <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Sulit</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
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
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-2"></i>
                Daftar Soal
            </h6>
            <div>
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-gear me-2"></i>Aksi Massal
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('activate')">
                            <i class="bi bi-check-circle me-2"></i>Aktifkan yang Dipilih
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('deactivate')">
                            <i class="bi bi-x-circle me-2"></i>Nonaktifkan yang Dipilih
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')">
                            <i class="bi bi-trash me-2"></i>Hapus yang Dipilih
                        </a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-sm btn-success" onclick="exportQuestions()">
                    <i class="bi bi-download me-2"></i>Ekspor
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($questions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="questionsTable">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                </th>
                                <th>Soal</th>
                                <th>Mata Pelajaran/Bab</th>
                                <th>Tingkat Kesulitan</th>
                                <th>Poin</th>
                                <th>Status</th>
                                <th>Penggunaan</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="question-checkbox" value="{{ $question->id }}">
                                    </td>
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
                                            <span class="badge bg-info mb-1">{{ $question->subject->name ?? 'Tidak Ditentukan' }}</span>
                                            <br>
                                            <small class="text-muted">{{ $question->chapter->name ?? 'Tidak Ditentukan' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $question->difficulty == 'easy' ? 'success' : ($question->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                            {{ $question->difficulty == 'easy' ? 'Mudah' : ($question->difficulty == 'medium' ? 'Sedang' : 'Sulit') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $question->points }}</span>
                                    </td>
                                    <td>
                                        @if($question->status == 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
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
                                            <a href="{{ route('admin.questions.edit', $question->id) }}" 
                                               class="btn btn-sm btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="duplicateQuestion({{ $question->id }})" title="Salin">
                                                <i class="bi bi-files"></i>
                                            </button>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" 
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button class="dropdown-item" onclick="toggleQuestionStatus({{ $question->id }})">
                                                            <i class="bi bi-{{ $question->status == 'active' ? 'x-circle' : 'check-circle' }} me-2"></i>
                                                            {{ $question->status == 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item" onclick="viewQuestionStats({{ $question->id }})">
                                                            <i class="bi bi-graph-up me-2"></i>Lihat Statistik
                                                        </button>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item text-danger" onclick="deleteQuestion({{ $question->id }})">
                                                            <i class="bi bi-trash me-2"></i>Hapus Soal
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $questions->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-question-circle fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">Tidak Ada Soal</h5>
                    <p class="text-gray-500">Tidak ditemukan soal yang sesuai dengan kriteria yang ditentukan.</p>
                    <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus me-2"></i>
                        Tambah Soal Baru
                    </a>
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

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.question-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function bulkAction(action) {
    const selectedQuestions = Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.value);
    
    if (selectedQuestions.length === 0) {
        Swal.fire({
            title: 'Peringatan!',
            text: 'Silakan pilih soal terlebih dahulu',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    let title, text, confirmText;
    
    switch(action) {
        case 'activate':
            title = 'Aktifkan Soal';
            text = `Apakah Anda ingin mengaktifkan ${selectedQuestions.length} soal?`;
            confirmText = 'Ya, Aktifkan';
            break;
        case 'deactivate':
            title = 'Nonaktifkan Soal';
            text = `Apakah Anda ingin menonaktifkan ${selectedQuestions.length} soal?`;
            confirmText = 'Ya, Nonaktifkan';
            break;
        case 'delete':
            title = 'Hapus Soal';
            text = `Apakah Anda yakin ingin menghapus ${selectedQuestions.length} soal? Tindakan ini tidak dapat dibatalkan.`;
            confirmText = 'Ya, Hapus';
            break;
    }
    
    Swal.fire({
        title: title,
        text: text,
        icon: action === 'delete' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        confirmButtonColor: action === 'delete' ? '#d33' : '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/guru/questions/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    action: action,
                    question_ids: selectedQuestions
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat menjalankan operasi',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function previewQuestion(questionId) {
    fetch(`/guru/questions/${questionId}`)
        .then(response => response.json())
        .then(question => {
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
                            <p class="mb-3">${question.tier1_question}</p>
                            ${question.tier1_image ? `<img src="/storage/${question.tier1_image}" class="img-fluid mb-3" style="max-height: 200px;">` : ''}
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier1_correct_answer === 'a' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier1_correct_answer === 'a' ? 'text-success fw-bold' : ''}">
                                            A) ${question.tier1_option_a}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier1_correct_answer === 'b' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier1_correct_answer === 'b' ? 'text-success fw-bold' : ''}">
                                            B) ${question.tier1_option_b}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier1_correct_answer === 'c' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier1_correct_answer === 'c' ? 'text-success fw-bold' : ''}">
                                            C) ${question.tier1_option_c}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier1_correct_answer === 'd' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier1_correct_answer === 'd' ? 'text-success fw-bold' : ''}">
                                            D) ${question.tier1_option_d}
                                        </label>
                                    </div>
                                </div>
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
                            <p class="mb-3">${question.tier2_question}</p>
                            ${question.tier2_image ? `<img src="/storage/${question.tier2_image}" class="img-fluid mb-3" style="max-height: 200px;">` : ''}
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier2_correct_answer === 'a' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier2_correct_answer === 'a' ? 'text-success fw-bold' : ''}">
                                            A) ${question.tier2_option_a}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier2_correct_answer === 'b' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier2_correct_answer === 'b' ? 'text-success fw-bold' : ''}">
                                            B) ${question.tier2_option_b}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier2_correct_answer === 'c' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier2_correct_answer === 'c' ? 'text-success fw-bold' : ''}">
                                            C) ${question.tier2_option_c}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier2_correct_answer === 'd' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier2_correct_answer === 'd' ? 'text-success fw-bold' : ''}">
                                            D) ${question.tier2_option_d}
                                        </label>
                                    </div>
                                </div>
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
                                    <p><strong>Tingkat Kesulitan:</strong> 
                                        <span class="badge bg-${question.difficulty === 'easy' ? 'success' : (question.difficulty === 'medium' ? 'warning' : 'danger')}">
                                            ${question.difficulty === 'easy' ? 'Mudah' : (question.difficulty === 'medium' ? 'Sedang' : 'Sulit')}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Poin:</strong> ${question.points}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-${question.status === 'active' ? 'success' : 'secondary'}">
                                            ${question.status === 'active' ? 'Aktif' : 'Tidak Aktif'}
                                        </span>
                                    </p>
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

function duplicateQuestion(questionId) {
    Swal.fire({
        title: 'Salin Soal',
        text: 'Apakah Anda ingin membuat salinan dari soal ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Salin',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/guru/questions/${questionId}/duplicate`, {
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
                        text: 'Soal berhasil disalin',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat menyalin soal',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function toggleQuestionStatus(questionId) {
    fetch(`/guru/questions/${questionId}/toggle-status`, {
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
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Terjadi kesalahan saat mengubah status soal',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

function deleteQuestion(questionId) {
    Swal.fire({
        title: 'Hapus Soal',
        text: 'Apakah Anda yakin ingin menghapus soal ini? Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/guru/questions/${questionId}`, {
                method: 'DELETE',
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
                        text: 'Soal berhasil dihapus',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat menghapus soal',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function exportQuestions() {
    const selectedQuestions = Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.value);
    
    Swal.fire({
        title: 'Ekspor Soal',
        text: selectedQuestions.length > 0 ? 
            `Apakah Anda ingin mengekspor ${selectedQuestions.length} soal yang dipilih?` : 
            'Apakah Anda ingin mengekspor semua soal?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ekspor',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/guru/questions/export';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            if (selectedQuestions.length > 0) {
                selectedQuestions.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'question_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
            }
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    });
}
</script>
@endpush
@endsection

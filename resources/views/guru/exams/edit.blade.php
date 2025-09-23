@extends('layouts.app')

@section('title', 'Edit Ujian')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Ujian
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.exams.index') }}">Ujian</a></li>
                    <li class="breadcrumb-item active">Edit Ujian</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>
                Kembali ke Ujian
            </a>
            <button type="button" class="btn btn-info" onclick="previewExam()">
                <i class="bi bi-eye me-2"></i>
                Pratinjau Ujian
            </button>
        </div>
    </div>

    <!-- Edit Exam Form -->
    <form action="{{ route('guru.exams.update', $exam->id) }}" method="POST" id="editExamForm">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-info-circle me-2"></i>
                    Informasi Dasar
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Judul Ujian <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $exam->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">Kode Ujian</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $exam->code) }}" readonly>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Kode ujian tidak dapat diubah</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('subject_id') is-invalid @enderror" 
                                id="subject_id" name="subject_id" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="duration" class="form-label">Durasi Ujian (menit) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                               id="duration" name="duration" value="{{ old('duration', $exam->duration) }}" min="5" max="300" required>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Ujian</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $exam->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Exam Settings -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-gear me-2"></i>
                    Pengaturan Ujian
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="max_attempts" class="form-label">Jumlah Percobaan yang Diizinkan</label>
                        <select class="form-select @error('max_attempts') is-invalid @enderror" 
                                id="max_attempts" name="max_attempts">
                            <option value="1" {{ old('max_attempts', $exam->max_attempts) == 1 ? 'selected' : '' }}>Satu Kali</option>
                            <option value="2" {{ old('max_attempts', $exam->max_attempts) == 2 ? 'selected' : '' }}>Dua Kali</option>
                            <option value="3" {{ old('max_attempts', $exam->max_attempts) == 3 ? 'selected' : '' }}>Tiga Kali</option>
                            <option value="0" {{ old('max_attempts', $exam->max_attempts) == 0 ? 'selected' : '' }}>Tidak Terbatas</option>
                        </select>
                        @error('max_attempts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="passing_score" class="form-label">Nilai Kelulusan (%)</label>
                        <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                               id="passing_score" name="passing_score" value="{{ old('passing_score', $exam->passing_score) }}" 
                               min="0" max="100">
                        @error('passing_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="show_results" class="form-label">Tampilkan Hasil</label>
                        <select class="form-select @error('show_results') is-invalid @enderror" 
                                id="show_results" name="show_results">
                            <option value="immediately" {{ old('show_results', $exam->show_results) == 'immediately' ? 'selected' : '' }}>Langsung Setelah Selesai</option>
                            <option value="after_exam" {{ old('show_results', $exam->show_results) == 'after_exam' ? 'selected' : '' }}>Setelah Ujian Berakhir</option>
                            <option value="manual" {{ old('show_results', $exam->show_results) == 'manual' ? 'selected' : '' }}>Manual oleh Guru</option>
                        </select>
                        @error('show_results')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="shuffle_questions" 
                                   name="shuffle_questions" value="1" {{ old('shuffle_questions', $exam->shuffle_questions) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shuffle_questions">
                                Acak Urutan Soal
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="shuffle_options" 
                                   name="shuffle_options" value="1" {{ old('shuffle_options', $exam->shuffle_options) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shuffle_options">
                                Acak Urutan Pilihan
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="show_correct_answers" 
                                   name="show_correct_answers" value="1" {{ old('show_correct_answers', $exam->show_correct_answers) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_correct_answers">
                                Tampilkan Jawaban Benar dalam Hasil
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="prevent_back" 
                                   name="prevent_back" value="1" {{ old('prevent_back', $exam->prevent_back) ? 'checked' : '' }}>
                            <label class="form-check-label" for="prevent_back">
                                Cegah Kembali ke Soal Sebelumnya
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Settings -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-calendar me-2"></i>
                    Pengaturan Waktu
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">Tanggal dan Waktu Mulai</label>
                        <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                               id="start_time" name="start_time" 
                               value="{{ old('start_time', $exam->start_time ? $exam->start_time->format('Y-m-d\TH:i') : '') }}">
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Kosongkan untuk mulai langsung</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label">Tanggal dan Waktu Berakhir</label>
                        <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                               id="end_time" name="end_time" 
                               value="{{ old('end_time', $exam->end_time ? $exam->end_time->format('Y-m-d\TH:i') : '') }}">
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Kosongkan untuk tidak membatasi waktu berakhir</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Questions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-question-circle me-2"></i>
                    Soal Ujian Saat Ini ({{ $exam->questions->count() }})
                </h6>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addMoreQuestions()">
                    <i class="bi bi-plus me-2"></i>
                    Tambah Soal
                </button>
            </div>
            <div class="card-body">
                @if($exam->questions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Urutan</th>
                                    <th>Soal</th>
                                    <th>Bab</th>
                                    <th>Tingkat Kesulitan</th>
                                    <th>Poin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="current_questions">
                                @foreach($exam->questions as $index => $question)
                                    <tr data-question-id="{{ $question->id }}">
                                        <td>
                                            <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                            <div class="btn-group-vertical btn-group-sm ms-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        onclick="moveQuestion({{ $question->id }}, 'up')" title="Pindah ke Atas">
                                                    <i class="bi bi-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        onclick="moveQuestion({{ $question->id }}, 'down')" title="Pindah ke Bawah">
                                                    <i class="bi bi-arrow-down"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" title="{{ $question->tier1_question }}">
                                                {{ Str::limit($question->tier1_question, 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $question->chapter->name ?? 'Tidak Ditentukan' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $question->difficulty == 'easy' ? 'success' : ($question->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                                {{ $question->difficulty == 'easy' ? 'Mudah' : ($question->difficulty == 'medium' ? 'Sedang' : 'Sulit') }}
                                            </span>
                                        </td>
                                        <td>{{ $question->points }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info" 
                                                        onclick="previewQuestion({{ $question->id }})" title="Pratinjau">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="removeQuestion({{ $question->id }})" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-question-circle fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-600">Belum Ada Soal</h5>
                        <p class="text-gray-500">Belum ada soal yang ditambahkan untuk ujian ini.</p>
                        <button type="button" class="btn btn-primary" onclick="addMoreQuestions()">
                            <i class="bi bi-plus me-2"></i>
                            Tambah Soal
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Exam Statistics -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-graph-up me-2"></i>
                    Statistik Ujian
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-primary">{{ $exam->questions->count() }}</h5>
                            <small class="text-muted">Total Soal</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-success">{{ $exam->questions->sum('points') }}</h5>
                            <small class="text-muted">Total Poin</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-info">{{ $exam->participants_count ?? 0 }}</h5>
                            <small class="text-muted">Jumlah Peserta</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-warning">{{ $exam->duration }}</h5>
                            <small class="text-muted">Durasi (menit)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>
                        Batal
                    </a>
                    <div>
                        @if($exam->status == 'draft')
                            <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                                <i class="bi bi-file-earmark me-2"></i>
                                Simpan sebagai Draf
                            </button>
                            <button type="submit" name="action" value="active" class="btn btn-success me-2">
                                <i class="bi bi-play me-2"></i>
                                Simpan dan Aktifkan
                            </button>
                        @endif
                        <button type="submit" name="action" value="update" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Add Questions Modal -->
<div class="modal fade" id="addQuestionsModal" tabindex="-1" aria-labelledby="addQuestionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuestionsModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    Tambah Soal ke Ujian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="modal_filter_chapter" class="form-label">Filter berdasarkan Bab</label>
                        <select class="form-select" id="modal_filter_chapter">
                            <option value="">Semua Bab</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="modal_filter_difficulty" class="form-label">Filter berdasarkan Tingkat Kesulitan</label>
                        <select class="form-select" id="modal_filter_difficulty">
                            <option value="">Semua Tingkat</option>
                            <option value="easy">Mudah</option>
                            <option value="medium">Sedang</option>
                            <option value="hard">Sulit</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary" id="modal_load_questions">
                                <i class="bi bi-search me-2"></i>
                                Muat Soal
                            </button>
                        </div>
                    </div>
                </div>
                <div id="modal_questions_list">
                    <!-- Questions will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="addSelectedQuestions()">
                    <i class="bi bi-plus me-2"></i>
                    Tambah Soal Terpilih
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectSelect = document.getElementById('subject_id');
    const modalChapterSelect = document.getElementById('modal_filter_chapter');
    
    // Load chapters when subject changes
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        modalChapterSelect.innerHTML = '<option value="">Semua Bab</option>';
        
        if (subjectId) {
            fetch(`/guru/subjects/${subjectId}/chapters`)
                .then(response => response.json())
                .then(chapters => {
                    chapters.forEach(chapter => {
                        const option = document.createElement('option');
                        option.value = chapter.id;
                        option.textContent = chapter.name;
                        modalChapterSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading chapters:', error);
                });
        }
    });
    
    // Load chapters on page load
    if (subjectSelect.value) {
        subjectSelect.dispatchEvent(new Event('change'));
    }
});

function addMoreQuestions() {
    const subjectId = document.getElementById('subject_id').value;
    
    if (!subjectId) {
        Swal.fire({
            title: 'Peringatan!',
            text: 'Silakan pilih mata pelajaran terlebih dahulu',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    new bootstrap.Modal(document.getElementById('addQuestionsModal')).show();
}

document.getElementById('modal_load_questions').addEventListener('click', function() {
    const subjectId = document.getElementById('subject_id').value;
    const chapterId = document.getElementById('modal_filter_chapter').value;
    const difficulty = document.getElementById('modal_filter_difficulty').value;
    
    const params = new URLSearchParams({
        subject_id: subjectId,
        exclude_exam: {{ $exam->id }},
        ...(chapterId && { chapter_id: chapterId }),
        ...(difficulty && { difficulty: difficulty })
    });
    
    fetch(`/guru/questions/search?${params}`)
        .then(response => response.json())
        .then(questions => {
            displayModalQuestions(questions);
        })
        .catch(error => {
            console.error('Error loading questions:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat soal',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
});

function displayModalQuestions(questions) {
    const container = document.getElementById('modal_questions_list');
    
    if (questions.length === 0) {
        container.innerHTML = '<p class="text-muted">Tidak ditemukan soal yang sesuai dengan kriteria yang ditentukan.</p>';
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr>';
    html += '<th><input type="checkbox" id="modal_select_all_questions"></th>';
    html += '<th>Soal</th><th>Bab</th><th>Tingkat Kesulitan</th><th>Poin</th>';
    html += '</tr></thead><tbody>';
    
    questions.forEach(question => {
        html += `<tr>
            <td><input type="checkbox" name="modal_selected_questions[]" value="${question.id}" class="modal-question-checkbox"></td>
            <td><div class="text-truncate" style="max-width: 300px;" title="${question.tier1_question}">${question.tier1_question.substring(0, 100)}...</div></td>
            <td><span class="badge bg-info">${question.chapter ? question.chapter.name : 'Tidak Ditentukan'}</span></td>
            <td><span class="badge bg-${getDifficultyColor(question.difficulty)}">${getDifficultyText(question.difficulty)}</span></td>
            <td>${question.points}</td>
        </tr>`;
    });
    
    html += '</tbody></table></div>';
    container.innerHTML = html;
    
    // Add select all functionality
    document.getElementById('modal_select_all_questions').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.modal-question-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
}

function addSelectedQuestions() {
    const selectedQuestions = document.querySelectorAll('.modal-question-checkbox:checked');
    
    if (selectedQuestions.length === 0) {
        Swal.fire({
            title: 'Peringatan!',
            text: 'Silakan pilih soal untuk ditambahkan',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    const questionIds = Array.from(selectedQuestions).map(cb => cb.value);
    
    fetch(`/guru/exams/{{ $exam->id }}/questions`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ question_ids: questionIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Soal berhasil ditambahkan',
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
            text: error.message || 'Terjadi kesalahan saat menambah soal',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

function removeQuestion(questionId) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus soal ini dari ujian?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/guru/exams/{{ $exam->id }}/questions/${questionId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
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

function moveQuestion(questionId, direction) {
    fetch(`/guru/exams/{{ $exam->id }}/questions/${questionId}/move`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ direction: direction })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Terjadi kesalahan saat memindah soal',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

function previewQuestion(questionId) {
    fetch(`/guru/questions/${questionId}`)
        .then(response => response.json())
        .then(question => {
            Swal.fire({
                title: 'Pratinjau Soal',
                html: `
                    <div class="text-start">
                        <h6>Soal Utama:</h6>
                        <p>${question.tier1_question}</p>
                        <h6>Pilihan:</h6>
                        <ul>
                            <li>A) ${question.tier1_option_a}</li>
                            <li>B) ${question.tier1_option_b}</li>
                            <li>C) ${question.tier1_option_c}</li>
                            <li>D) ${question.tier1_option_d}</li>
                        </ul>
                        <h6>Soal Justifikasi:</h6>
                        <p>${question.tier2_question}</p>
                        <h6>Pilihan Justifikasi:</h6>
                        <ul>
                            <li>A) ${question.tier2_option_a}</li>
                            <li>B) ${question.tier2_option_b}</li>
                            <li>C) ${question.tier2_option_c}</li>
                            <li>D) ${question.tier2_option_d}</li>
                        </ul>
                    </div>
                `,
                width: '800px',
                confirmButtonText: 'Tutup'
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat soal',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
}

function previewExam() {
    window.open(`/guru/exams/{{ $exam->id }}/preview`, '_blank');
}

function getDifficultyColor(difficulty) {
    switch(difficulty) {
        case 'easy': return 'success';
        case 'medium': return 'warning';
        case 'hard': return 'danger';
        default: return 'secondary';
    }
}

function getDifficultyText(difficulty) {
    switch(difficulty) {
        case 'easy': return 'Mudah';
        case 'medium': return 'Sedang';
        case 'hard': return 'Sulit';
        default: return 'Tidak Ditentukan';
    }
}

// Form validation
document.getElementById('editExamForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const subjectId = document.getElementById('subject_id').value;
    const duration = document.getElementById('duration').value;
    
    if (!title || !subjectId || !duration) {
        e.preventDefault();
        Swal.fire({
            title: 'Error!',
            text: 'Silakan isi semua field yang diperlukan',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
});
</script>
@endpush
@endsection

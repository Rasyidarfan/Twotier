@extends('layouts.app')

@section('title', 'Buat Ujian Baru')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-plus-circle me-2"></i>
                Buat Ujian Baru
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.exams.index') }}">Ujian</a></li>
                    <li class="breadcrumb-item active">Buat Ujian Baru</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Kembali ke Ujian
        </a>
    </div>

    <!-- Create Exam Form -->
    <form action="{{ route('guru.exams.store') }}" method="POST" id="createExamForm">
        @csrf
        
        <!-- Basic Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-info-circle me-2"></i>
                    Informasi Dasar
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Ujian <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('subject_id') is-invalid @enderror"
                                id="subject_id" name="subject_id" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="duration_minutes" class="form-label">Durasi Ujian (menit) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                               id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="5" max="300" required>
                        @error('duration_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="grade" class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select @error('grade') is-invalid @enderror"
                                id="grade" name="grade" required>
                            <option value="">Pilih Kelas</option>
                            <option value="X" {{ old('grade') == 'X' ? 'selected' : '' }}>X</option>
                            <option value="XI" {{ old('grade') == 'XI' ? 'selected' : '' }}>XI</option>
                            <option value="XII" {{ old('grade') == 'XII' ? 'selected' : '' }}>XII</option>
                        </select>
                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                        <select class="form-select @error('semester') is-invalid @enderror"
                                id="semester" name="semester" required>
                            <option value="">Pilih Semester</option>
                            <option value="gasal" {{ old('semester') == 'gasal' ? 'selected' : '' }}>Gasal</option>
                            <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Ujian</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="shuffle_questions"
                                   name="shuffle_questions" value="1" {{ old('shuffle_questions') ? 'checked' : '' }}>
                            <label class="form-check-label" for="shuffle_questions">
                                Acak Urutan Soal
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="show_result_immediately"
                                   name="show_result_immediately" value="1" {{ old('show_result_immediately') ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_result_immediately">
                                Tampilkan Hasil Langsung Setelah Selesai
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Question Selection -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-question-circle me-2"></i>
                    Pilih Soal
                </h6>
            </div>
            <div class="card-body">
                <!-- Hidden field to maintain backend compatibility -->
                <input type="hidden" name="question_selection_type" value="manual">

                <!-- Manual Question Selection -->
                <div id="manual_selection" class="question-selection-method">
                    <h6 class="text-secondary mb-3">Pilih Soal Secara Manual</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="filter_chapter" class="form-label">Filter berdasarkan Bab</label>
                            <select class="form-select" id="filter_chapter" name="filter_chapter">
                                <option value="">Semua Bab</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-primary" id="load_questions">
                                    <i class="bi bi-search me-2"></i>
                                    Muat Soal
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="questions_list" class="mt-3">
                        <!-- Questions will be loaded here -->
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
                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                            <i class="bi bi-file-earmark me-2"></i>
                            Simpan sebagai Draf
                        </button>
                        <button type="submit" name="action" value="active" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Buat dan Aktifkan Ujian
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectSelect = document.getElementById('subject_id');
    const chapterSelect = document.getElementById('filter_chapter');

    // Load chapters when subject changes
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        chapterSelect.innerHTML = '<option value="">Semua Bab</option>';

        if (subjectId) {
            fetch(`/guru/subjects/${subjectId}/chapters`)
                .then(response => response.json())
                .then(chapters => {
                    chapters.forEach(chapter => {
                        const option = document.createElement('option');
                        option.value = chapter.id;
                        option.textContent = chapter.name;
                        chapterSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading chapters:', error);
                });
        }
    });

    // Load questions for manual selection
    document.getElementById('load_questions').addEventListener('click', function() {
        const subjectId = subjectSelect.value;
        const chapterId = chapterSelect.value;

        if (!subjectId) {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Silakan pilih mata pelajaran terlebih dahulu',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        const params = new URLSearchParams({
            subject_id: subjectId,
            ...(chapterId && { chapter_id: chapterId })
        });

        fetch(`/guru/questions/search?${params}`)
            .then(response => response.json())
            .then(questions => {
                displayQuestions(questions);
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

    function displayQuestions(questions) {
        const container = document.getElementById('questions_list');

        if (questions.length === 0) {
            container.innerHTML = '<p class="text-muted">Tidak ditemukan soal yang sesuai dengan kriteria yang ditentukan.</p>';
            return;
        }

        let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr>';
        html += '<th><input type="checkbox" id="select_all_questions"></th>';
        html += '<th>Soal</th><th>Bab</th><th>Poin</th>';
        html += '</tr></thead><tbody>';

        questions.forEach(question => {
            html += `<tr>
                <td><input type="checkbox" name="questions[]" value="${question.id}" class="question-checkbox"></td>
                <td><div class="text-truncate" style="max-width: 400px;" title="${question.tier1_question}">${question.tier1_question.substring(0, 120)}...</div></td>
                <td><span class="badge bg-info">${question.chapter ? question.chapter.name : 'Tidak Ditentukan'}</span></td>
                <td>${question.points || 10}</td>
            </tr>`;
        });

        html += '</tbody></table></div>';
        container.innerHTML = html;

        // Add select all functionality
        document.getElementById('select_all_questions').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.question-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Form validation
    document.getElementById('createExamForm').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const subjectId = document.getElementById('subject_id').value;
        const durationMinutes = document.getElementById('duration_minutes').value;
        const grade = document.getElementById('grade').value;
        const semester = document.getElementById('semester').value;

        if (!title || !subjectId || !durationMinutes || !grade || !semester) {
            e.preventDefault();
            Swal.fire({
                title: 'Error!',
                text: 'Silakan isi semua field yang diperlukan',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        const selectedQuestions = document.querySelectorAll('.question-checkbox:checked');
        if (selectedQuestions.length === 0) {
            e.preventDefault();
            Swal.fire({
                title: 'Peringatan!',
                text: 'Silakan pilih soal untuk ujian',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
    });
});
</script>
@endpush
@endsection

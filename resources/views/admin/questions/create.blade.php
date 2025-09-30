@extends('layouts.app')

@section('title', 'Tambah Soal Baru')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-plus-circle me-2"></i>
                Tambah Soal Baru
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Kelola Soal</a></li>
                    <li class="breadcrumb-item active">Tambah Soal</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Kembali ke Daftar Soal
        </a>
    </div>

    <!-- Create Question Form -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i>
                        Informasi Soal
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" required>
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
                            <div class="col-md-6">
                                <label for="chapter_id" class="form-label">Bab <span class="text-danger">*</span></label>
                                <select class="form-select @error('chapter_id') is-invalid @enderror" id="chapter_id" name="chapter_id" required>
                                    <option value="">Pilih Bab</option>
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}" {{ old('chapter_id') == $chapter->id ? 'selected' : '' }}>
                                            {{ $chapter->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chapter_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Hidden difficulty field with default value -->
                        <input type="hidden" name="difficulty" value="sedang">

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="points" class="form-label">Poin <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('points') is-invalid @enderror"
                                       id="points" name="points" value="{{ old('points', 10) }}" min="1" max="100" required>
                                @error('points')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tier 1 Question -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-1-circle me-2"></i>
                                    Pertanyaan Tier 1 (Pengetahuan Konsep)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="tier1_question" class="form-label">Teks Pertanyaan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('tier1_question') is-invalid @enderror"
                                              id="tier1_question" name="tier1_question" rows="4" required
                                              placeholder="Masukkan pertanyaan utama di sini..."
                                              style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">{{ old('tier1_question') }}</textarea>
                                    @error('tier1_question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tier 1 Options -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tier1_options_0" class="form-label">Pilihan أ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier1_options.0') is-invalid @enderror"
                                               id="tier1_options_0" name="tier1_options[0]" value="{{ old('tier1_options.0') }}" required
                                               placeholder="Masukkan pilihan أ"
                                               style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                        @error('tier1_options.0')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier1_options_1" class="form-label">Pilihan ب <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier1_options.1') is-invalid @enderror"
                                               id="tier1_options_1" name="tier1_options[1]" value="{{ old('tier1_options.1') }}" required
                                               placeholder="Masukkan pilihan ب"
                                               style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                        @error('tier1_options.1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier1_options_2" class="form-label">Pilihan ج <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier1_options.2') is-invalid @enderror"
                                               id="tier1_options_2" name="tier1_options[2]" value="{{ old('tier1_options.2') }}" required
                                               placeholder="Masukkan pilihan ج"
                                               style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                        @error('tier1_options.2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier1_options_3" class="form-label">Pilihan د <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier1_options.3') is-invalid @enderror"
                                               id="tier1_options_3" name="tier1_options[3]" value="{{ old('tier1_options.3') }}" required
                                               placeholder="Masukkan pilihan د"
                                               style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                        @error('tier1_options.3')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier1_options_4" class="form-label">Pilihan ه <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier1_options.4') is-invalid @enderror"
                                               id="tier1_options_4" name="tier1_options[4]" value="{{ old('tier1_options.4') }}" required
                                               placeholder="Masukkan pilihan ه"
                                               style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                        @error('tier1_options.4')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="tier1_correct_answer" class="form-label">Jawaban Benar <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tier1_correct_answer') is-invalid @enderror" 
                                            id="tier1_correct_answer" name="tier1_correct_answer" required>
                                        <option value="">Pilih Jawaban yang Benar</option>
                                        <option value="0" {{ old('tier1_correct_answer') == '0' ? 'selected' : '' }}>أ</option>
                                        <option value="1" {{ old('tier1_correct_answer') == '1' ? 'selected' : '' }}>ب</option>
                                        <option value="2" {{ old('tier1_correct_answer') == '2' ? 'selected' : '' }}>ج</option>
                                        <option value="3" {{ old('tier1_correct_answer') == '3' ? 'selected' : '' }}>د</option>
                                        <option value="4" {{ old('tier1_correct_answer') == '4' ? 'selected' : '' }}>ه</option>
                                    </select>
                                    @error('tier1_correct_answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tier 2 Question -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-2-circle me-2"></i>
                                    Pertanyaan Tier 2 (Alasan/Reasoning)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="tier2_question" class="form-label">Teks Pertanyaan <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('tier2_question') is-invalid @enderror"
                                              id="tier2_question" name="tier2_question" rows="4" required
                                              placeholder="Masukkan pertanyaan tentang alasan pemilihan jawaban..."
                                              style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">{{ old('tier2_question') }}</textarea>
                                    @error('tier2_question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tier 2 Options -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tier2_options_0" class="form-label">Pilihan أ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier2_options.0') is-invalid @enderror"
                                               id="tier2_options_0" name="tier2_options[0]" value="{{ old('tier2_options.0') }}" required
                                               placeholder="Masukkan alasan أ"
                                               style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                        @error('tier2_options.0')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier2_options_1" class="form-label">Pilihan ب <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier2_options.1') is-invalid @enderror"
                                               id="tier2_options_1" name="tier2_options[1]" value="{{ old('tier2_options.1') }}" required
                                               placeholder="Masukkan alasan ب"
                                               style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                        @error('tier2_options.1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier2_options_2" class="form-label">Pilihan ج <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier2_options.2') is-invalid @enderror"
                                               id="tier2_options_2" name="tier2_options[2]" value="{{ old('tier2_options.2') }}" required
                                               placeholder="Masukkan alasan ج"
                                               style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                        @error('tier2_options.2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier2_options_3" class="form-label">Pilihan د <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier2_options.3') is-invalid @enderror"
                                               id="tier2_options_3" name="tier2_options[3]" value="{{ old('tier2_options.3') }}" required
                                               placeholder="Masukkan alasan د"
                                               style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                        @error('tier2_options.3')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier2_options_4" class="form-label">Pilihan ه <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier2_options.4') is-invalid @enderror"
                                               id="tier2_options_4" name="tier2_options[4]" value="{{ old('tier2_options.4') }}" required
                                               placeholder="Masukkan alasan ه"
                                               style="direction: rtl; text-align: right; font-family: 'Amiri', 'Noto Sans Arabic', serif;">
                                        @error('tier2_options.4')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="tier2_correct_answer" class="form-label">Jawaban Benar <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tier2_correct_answer') is-invalid @enderror" 
                                            id="tier2_correct_answer" name="tier2_correct_answer" required>
                                        <option value="">Pilih Alasan yang Benar</option>
                                        <option value="0" {{ old('tier2_correct_answer') == '0' ? 'selected' : '' }}>أ</option>
                                        <option value="1" {{ old('tier2_correct_answer') == '1' ? 'selected' : '' }}>ب</option>
                                        <option value="2" {{ old('tier2_correct_answer') == '2' ? 'selected' : '' }}>ج</option>
                                        <option value="3" {{ old('tier2_correct_answer') == '3' ? 'selected' : '' }}>د</option>
                                        <option value="4" {{ old('tier2_correct_answer') == '4' ? 'selected' : '' }}>ه</option>
                                    </select>
                                    @error('tier2_correct_answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Informasi Tambahan (Opsional)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="explanation" class="form-label">Penjelasan Jawaban</label>
                                    <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                              id="explanation" name="explanation" rows="3" 
                                              placeholder="Berikan penjelasan detail mengapa jawaban tersebut benar...">{{ old('explanation') }}</textarea>
                                    @error('explanation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tag/Kata Kunci</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" value="{{ old('tags') }}" 
                                           placeholder="contoh: qowaid, nahwu, fiil madhi">
                                    <small class="form-text text-muted">Pisahkan tag dengan koma (,)</small>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>
                                Batal
                            </a>
                            <div>
                                <button type="submit" name="action" value="save" class="btn btn-primary me-2">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Simpan Soal
                                </button>
                                <button type="submit" name="action" value="save_and_new" class="btn btn-success">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Simpan & Tambah Lagi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
        chapterSelect.innerHTML = '<option value="">Pilih Bab</option>';
        
        if (subjectId) {
            // Show loading state
            chapterSelect.innerHTML = '<option value="">Memuat bab...</option>';
            chapterSelect.disabled = true;

            fetch(`/admin/subjects/${subjectId}/chapters`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(chapters => {
                    chapterSelect.innerHTML = '<option value="">Pilih Bab</option>';
                    chapterSelect.disabled = false;

                    if (chapters.length > 0) {
                        chapters.forEach(chapter => {
                            const option = document.createElement('option');
                            option.value = chapter.id;
                            option.textContent = `${chapter.name} (Kelas ${chapter.grade} - ${chapter.semester === 'gasal' ? 'Gasal' : 'Genap'})`;
                            chapterSelect.appendChild(option);
                        });
                    } else {
                        chapterSelect.innerHTML = '<option value="">Tidak ada bab tersedia untuk mata pelajaran ini</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading chapters:', error);
                    chapterSelect.innerHTML = '<option value="">Error memuat bab - silakan refresh halaman</option>';
                    chapterSelect.disabled = false;

                    // Show user-friendly error message
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal memuat daftar bab. Silakan refresh halaman atau coba lagi.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        } else {
            chapterSelect.disabled = false;
        }
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                title: 'Peringatan!',
                text: 'Mohon lengkapi semua field yang wajib diisi',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    });

    // Preview options as user types
    const tier1Options = document.querySelectorAll('[name^="tier1_options"]');
    const tier2Options = document.querySelectorAll('[name^="tier2_options"]');
    
    function updatePreview(options, previewId) {
        options.forEach((input, index) => {
            input.addEventListener('input', function() {
                console.log(`Option ${index}: ${this.value}`);
            });
        });
    }
    
    updatePreview(tier1Options, 'tier1-preview');
    updatePreview(tier2Options, 'tier2-preview');
});

// Success/Error messages
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endpush
@endsection
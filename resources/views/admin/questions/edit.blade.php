@extends('layouts.app')

@section('title', 'Edit Soal')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Soal
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Kelola Soal</a></li>
                    <li class="breadcrumb-item active">Edit Soal</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>
                Kembali ke Daftar
            </a>
            <a href="{{ route('admin.questions.show', $question->id) }}" class="btn btn-info">
                <i class="bi bi-eye me-2"></i>
                Lihat Soal
            </a>
        </div>
    </div>

    <!-- Edit Question Form -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Informasi Soal
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ (old('subject_id', $question->chapter->subject_id ?? '') == $subject->id) ? 'selected' : '' }}>
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
                                        <option value="{{ $chapter->id }}" {{ (old('chapter_id', $question->chapter_id) == $chapter->id) ? 'selected' : '' }}>
                                            {{ $chapter->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chapter_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="difficulty" class="form-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                                <select class="form-select @error('difficulty') is-invalid @enderror" id="difficulty" name="difficulty" required>
                                    <option value="">Pilih Tingkat Kesulitan</option>
                                    <option value="mudah" {{ old('difficulty', $question->difficulty) == 'mudah' ? 'selected' : '' }}>Mudah</option>
                                    <option value="sedang" {{ old('difficulty', $question->difficulty) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="sulit" {{ old('difficulty', $question->difficulty) == 'sulit' ? 'selected' : '' }}>Sulit</option>
                                </select>
                                @error('difficulty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="points" class="form-label">Poin <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                       id="points" name="points" value="{{ old('points', 10) }}" min="1" max="100" required>
                                @error('points')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                                    <option value="1" {{ old('is_active', $question->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active', $question->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Nonaktif</option>
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
                                              placeholder="Masukkan pertanyaan utama di sini...">{{ old('tier1_question', $question->tier1_question) }}</textarea>
                                    @error('tier1_question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tier 1 Options -->
                                <div class="row">
                                    @if(is_array($question->tier1_options))
                                        @foreach($question->tier1_options as $index => $option)
                                            <div class="col-md-6 mb-3">
                                                <label for="tier1_options_{{ $index }}" class="form-label">Pilihan {{ chr(65 + $index) }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('tier1_options.'.$index) is-invalid @enderror" 
                                                       id="tier1_options_{{ $index }}" name="tier1_options[{{ $index }}]" 
                                                       value="{{ old('tier1_options.'.$index, $option) }}" required 
                                                       placeholder="Masukkan pilihan {{ chr(65 + $index) }}">
                                                @error('tier1_options.'.$index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    @else
                                        @for($i = 0; $i < 5; $i++)
                                            <div class="col-md-6 mb-3">
                                                <label for="tier1_options_{{ $i }}" class="form-label">Pilihan {{ chr(65 + $i) }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('tier1_options.'.$i) is-invalid @enderror" 
                                                       id="tier1_options_{{ $i }}" name="tier1_options[{{ $i }}]" 
                                                       value="{{ old('tier1_options.'.$i) }}" required 
                                                       placeholder="Masukkan pilihan {{ chr(65 + $i) }}">
                                                @error('tier1_options.'.$i)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endfor
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="tier1_correct_answer" class="form-label">Jawaban Benar <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tier1_correct_answer') is-invalid @enderror" 
                                            id="tier1_correct_answer" name="tier1_correct_answer" required>
                                        <option value="">Pilih Jawaban yang Benar</option>
                                        <option value="0" {{ old('tier1_correct_answer', $question->tier1_correct_answer) == '0' ? 'selected' : '' }}>A</option>
                                        <option value="1" {{ old('tier1_correct_answer', $question->tier1_correct_answer) == '1' ? 'selected' : '' }}>B</option>
                                        <option value="2" {{ old('tier1_correct_answer', $question->tier1_correct_answer) == '2' ? 'selected' : '' }}>C</option>
                                        <option value="3" {{ old('tier1_correct_answer', $question->tier1_correct_answer) == '3' ? 'selected' : '' }}>D</option>
                                        <option value="4" {{ old('tier1_correct_answer', $question->tier1_correct_answer) == '4' ? 'selected' : '' }}>E</option>
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
                                              placeholder="Masukkan pertanyaan tentang alasan pemilihan jawaban...">{{ old('tier2_question', $question->tier2_question) }}</textarea>
                                    @error('tier2_question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tier 2 Options -->
                                <div class="row">
                                    @if(is_array($question->tier2_options))
                                        @foreach($question->tier2_options as $index => $option)
                                            <div class="col-md-6 mb-3">
                                                <label for="tier2_options_{{ $index }}" class="form-label">Pilihan {{ chr(65 + $index) }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('tier2_options.'.$index) is-invalid @enderror" 
                                                       id="tier2_options_{{ $index }}" name="tier2_options[{{ $index }}]" 
                                                       value="{{ old('tier2_options.'.$index, $option) }}" required 
                                                       placeholder="Masukkan alasan {{ chr(65 + $index) }}">
                                                @error('tier2_options.'.$index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    @else
                                        @for($i = 0; $i < 5; $i++)
                                            <div class="col-md-6 mb-3">
                                                <label for="tier2_options_{{ $i }}" class="form-label">Pilihan {{ chr(65 + $i) }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('tier2_options.'.$i) is-invalid @enderror" 
                                                       id="tier2_options_{{ $i }}" name="tier2_options[{{ $i }}]" 
                                                       value="{{ old('tier2_options.'.$i) }}" required 
                                                       placeholder="Masukkan alasan {{ chr(65 + $i) }}">
                                                @error('tier2_options.'.$i)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endfor
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="tier2_correct_answer" class="form-label">Jawaban Benar <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tier2_correct_answer') is-invalid @enderror" 
                                            id="tier2_correct_answer" name="tier2_correct_answer" required>
                                        <option value="">Pilih Alasan yang Benar</option>
                                        <option value="0" {{ old('tier2_correct_answer', $question->tier2_correct_answer) == '0' ? 'selected' : '' }}>A</option>
                                        <option value="1" {{ old('tier2_correct_answer', $question->tier2_correct_answer) == '1' ? 'selected' : '' }}>B</option>
                                        <option value="2" {{ old('tier2_correct_answer', $question->tier2_correct_answer) == '2' ? 'selected' : '' }}>C</option>
                                        <option value="3" {{ old('tier2_correct_answer', $question->tier2_correct_answer) == '3' ? 'selected' : '' }}>D</option>
                                        <option value="4" {{ old('tier2_correct_answer', $question->tier2_correct_answer) == '4' ? 'selected' : '' }}>E</option>
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
                                              placeholder="Berikan penjelasan detail mengapa jawaban tersebut benar...">{{ old('explanation', $question->explanation ?? '') }}</textarea>
                                    @error('explanation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tag/Kata Kunci</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" value="{{ old('tags', $question->tags ?? '') }}" 
                                           placeholder="contoh: qowaid, nahwu, fiil madhi">
                                    <small class="form-text text-muted">Pisahkan tag dengan koma (,)</small>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Question Statistics -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="bi bi-graph-up me-2"></i>
                                    Statistik Penggunaan Soal
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h5 class="text-primary">{{ $question->examQuestions->count() ?? 0 }}</h5>
                                            <small class="text-muted">Digunakan dalam Ujian</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h5 class="text-success">0</h5>
                                            <small class="text-muted">Benar-Benar</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h5 class="text-warning">0</h5>
                                            <small class="text-muted">Benar-Salah</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h5 class="text-info">
                                                {{ $question->created_at ? $question->created_at->format('d M Y') : '-' }}
                                            </h5>
                                            <small class="text-muted">Dibuat Tanggal</small>
                                        </div>
                                    </div>
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
                                <button type="submit" name="action" value="update" class="btn btn-primary me-2">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Simpan Perubahan
                                </button>
                                <button type="submit" name="action" value="update_and_continue" class="btn btn-success">
                                    <i class="bi bi-arrow-right me-2"></i>
                                    Simpan & Edit Lainnya
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
        const currentChapterId = '{{ old("chapter_id", $question->chapter_id) }}';
        
        chapterSelect.innerHTML = '<option value="">Pilih Bab</option>';
        
        if (subjectId) {
            fetch(`/admin/subjects/${subjectId}/chapters`)
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
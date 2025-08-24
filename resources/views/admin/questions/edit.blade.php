@extends('layouts.app')

@section('title', 'تعديل السؤال')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-pencil-square me-2"></i>
                تعديل السؤال
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">الأسئلة</a></li>
                    <li class="breadcrumb-item active">تعديل السؤال</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>
                العودة للأسئلة
            </a>
            <a href="{{ route('admin.questions.show', $question->id) }}" class="btn btn-info">
                <i class="bi bi-eye me-2"></i>
                عرض السؤال
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
                        تعديل بيانات السؤال
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="subject_id" class="form-label">المادة <span class="text-danger">*</span></label>
                                <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" required>
                                    <option value="">اختر المادة</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ (old('subject_id', $question->subject_id) == $subject->id) ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="chapter_id" class="form-label">الفصل <span class="text-danger">*</span></label>
                                <select class="form-select @error('chapter_id') is-invalid @enderror" id="chapter_id" name="chapter_id" required>
                                    <option value="">اختر الفصل</option>
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
                                <label for="difficulty" class="form-label">مستوى الصعوبة <span class="text-danger">*</span></label>
                                <select class="form-select @error('difficulty') is-invalid @enderror" id="difficulty" name="difficulty" required>
                                    <option value="">اختر المستوى</option>
                                    <option value="easy" {{ old('difficulty', $question->difficulty) == 'easy' ? 'selected' : '' }}>سهل</option>
                                    <option value="medium" {{ old('difficulty', $question->difficulty) == 'medium' ? 'selected' : '' }}>متوسط</option>
                                    <option value="hard" {{ old('difficulty', $question->difficulty) == 'hard' ? 'selected' : '' }}>صعب</option>
                                </select>
                                @error('difficulty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="points" class="form-label">النقاط <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                       id="points" name="points" value="{{ old('points', $question->points) }}" min="1" max="10" required>
                                @error('points')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $question->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('status', $question->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tier 1 Question -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-1-circle me-2"></i>
                                    السؤال الأساسي (المستوى الأول)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="tier1_question" class="form-label">نص السؤال <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('tier1_question') is-invalid @enderror" 
                                              id="tier1_question" name="tier1_question" rows="4" required>{{ old('tier1_question', $question->tier1_question) }}</textarea>
                                    @error('tier1_question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tier1_image" class="form-label">صورة السؤال (اختياري)</label>
                                    @if($question->tier1_image)
                                        <div class="current-image mb-2">
                                            <p class="text-muted">الصورة الحالية:</p>
                                            <img src="{{ asset('storage/' . $question->tier1_image) }}" class="img-thumbnail" style="max-width: 200px;">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="remove_tier1_image" id="remove_tier1_image">
                                                <label class="form-check-label text-danger" for="remove_tier1_image">
                                                    حذف الصورة الحالية
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('tier1_image') is-invalid @enderror" 
                                           id="tier1_image" name="tier1_image" accept="image/*">
                                    @error('tier1_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tier 1 Options -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tier1_option_a" class="form-label">الخيار أ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier1_option_a') is-invalid @enderror" 
                                               id="tier1_option_a" name="tier1_option_a" value="{{ old('tier1_option_a', $question->tier1_option_a) }}" required>
                                        @error('tier1_option_a')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier1_option_b" class="form-label">الخيار ب <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier1_option_b') is-invalid @enderror" 
                                               id="tier1_option_b" name="tier1_option_b" value="{{ old('tier1_option_b', $question->tier1_option_b) }}" required>
                                        @error('tier1_option_b')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier1_option_c" class="form-label">الخيار ج <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier1_option_c') is-invalid @enderror" 
                                               id="tier1_option_c" name="tier1_option_c" value="{{ old('tier1_option_c', $question->tier1_option_c) }}" required>
                                        @error('tier1_option_c')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier1_option_d" class="form-label">الخيار د <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier1_option_d') is-invalid @enderror" 
                                               id="tier1_option_d" name="tier1_option_d" value="{{ old('tier1_option_d', $question->tier1_option_d) }}" required>
                                        @error('tier1_option_d')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="tier1_correct_answer" class="form-label">الإجابة الصحيحة <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tier1_correct_answer') is-invalid @enderror" 
                                            id="tier1_correct_answer" name="tier1_correct_answer" required>
                                        <option value="">اختر الإجابة الصحيحة</option>
                                        <option value="a" {{ old('tier1_correct_answer', $question->tier1_correct_answer) == 'a' ? 'selected' : '' }}>أ</option>
                                        <option value="b" {{ old('tier1_correct_answer', $question->tier1_correct_answer) == 'b' ? 'selected' : '' }}>ب</option>
                                        <option value="c" {{ old('tier1_correct_answer', $question->tier1_correct_answer) == 'c' ? 'selected' : '' }}>ج</option>
                                        <option value="d" {{ old('tier1_correct_answer', $question->tier1_correct_answer) == 'd' ? 'selected' : '' }}>د</option>
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
                                    سؤال التبرير (المستوى الثاني)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="tier2_question" class="form-label">نص السؤال <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('tier2_question') is-invalid @enderror" 
                                              id="tier2_question" name="tier2_question" rows="4" required>{{ old('tier2_question', $question->tier2_question) }}</textarea>
                                    @error('tier2_question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tier2_image" class="form-label">صورة السؤال (اختياري)</label>
                                    @if($question->tier2_image)
                                        <div class="current-image mb-2">
                                            <p class="text-muted">الصورة الحالية:</p>
                                            <img src="{{ asset('storage/' . $question->tier2_image) }}" class="img-thumbnail" style="max-width: 200px;">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="remove_tier2_image" id="remove_tier2_image">
                                                <label class="form-check-label text-danger" for="remove_tier2_image">
                                                    حذف الصورة الحالية
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('tier2_image') is-invalid @enderror" 
                                           id="tier2_image" name="tier2_image" accept="image/*">
                                    @error('tier2_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tier 2 Options -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tier2_option_a" class="form-label">الخيار أ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier2_option_a') is-invalid @enderror" 
                                               id="tier2_option_a" name="tier2_option_a" value="{{ old('tier2_option_a', $question->tier2_option_a) }}" required>
                                        @error('tier2_option_a')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier2_option_b" class="form-label">الخيار ب <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier2_option_b') is-invalid @enderror" 
                                               id="tier2_option_b" name="tier2_option_b" value="{{ old('tier2_option_b', $question->tier2_option_b) }}" required>
                                        @error('tier2_option_b')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier2_option_c" class="form-label">الخيار ج <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier2_option_c') is-invalid @enderror" 
                                               id="tier2_option_c" name="tier2_option_c" value="{{ old('tier2_option_c', $question->tier2_option_c) }}" required>
                                        @error('tier2_option_c')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tier2_option_d" class="form-label">الخيار د <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('tier2_option_d') is-invalid @enderror" 
                                               id="tier2_option_d" name="tier2_option_d" value="{{ old('tier2_option_d', $question->tier2_option_d) }}" required>
                                        @error('tier2_option_d')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="tier2_correct_answer" class="form-label">الإجابة الصحيحة <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tier2_correct_answer') is-invalid @enderror" 
                                            id="tier2_correct_answer" name="tier2_correct_answer" required>
                                        <option value="">اختر الإجابة الصحيحة</option>
                                        <option value="a" {{ old('tier2_correct_answer', $question->tier2_correct_answer) == 'a' ? 'selected' : '' }}>أ</option>
                                        <option value="b" {{ old('tier2_correct_answer', $question->tier2_correct_answer) == 'b' ? 'selected' : '' }}>ب</option>
                                        <option value="c" {{ old('tier2_correct_answer', $question->tier2_correct_answer) == 'c' ? 'selected' : '' }}>ج</option>
                                        <option value="d" {{ old('tier2_correct_answer', $question->tier2_correct_answer) == 'd' ? 'selected' : '' }}>د</option>
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
                                    معلومات إضافية
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="explanation" class="form-label">شرح الإجابة (اختياري)</label>
                                    <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                              id="explanation" name="explanation" rows="3">{{ old('explanation', $question->explanation) }}</textarea>
                                    @error('explanation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tags" class="form-label">العلامات (اختياري)</label>
                                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                           id="tags" name="tags" value="{{ old('tags', $question->tags) }}" 
                                           placeholder="مثال: رياضيات، جبر، معادلات">
                                    <small class="form-text text-muted">افصل بين العلامات بفاصلة</small>
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
                                    إحصائيات السؤال
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h5 class="text-primary">{{ $question->usage_count ?? 0 }}</h5>
                                            <small class="text-muted">مرات الاستخدام</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h5 class="text-success">{{ $question->correct_answers ?? 0 }}</h5>
                                            <small class="text-muted">إجابات صحيحة</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h5 class="text-danger">{{ $question->wrong_answers ?? 0 }}</h5>
                                            <small class="text-muted">إجابات خاطئة</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <h5 class="text-info">
                                                {{ $question->usage_count > 0 ? round(($question->correct_answers / $question->usage_count) * 100, 1) : 0 }}%
                                            </h5>
                                            <small class="text-muted">معدل النجاح</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>
                                إلغاء
                            </a>
                            <div>
                                <button type="submit" name="action" value="update" class="btn btn-primary me-2">
                                    <i class="bi bi-check-circle me-2"></i>
                                    حفظ التعديلات
                                </button>
                                <button type="submit" name="action" value="update_and_continue" class="btn btn-success">
                                    <i class="bi bi-arrow-right me-2"></i>
                                    حفظ والانتقال للتالي
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
        
        chapterSelect.innerHTML = '<option value="">اختر الفصل</option>';
        
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
                title: 'خطأ!',
                text: 'يرجى ملء جميع الحقول المطلوبة',
                icon: 'error',
                confirmButtonText: 'موافق'
            });
        }
    });

    // Image preview for new uploads
    const imageInputs = document.querySelectorAll('input[type="file"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create or update preview
                    let preview = input.parentNode.querySelector('.new-image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.className = 'new-image-preview mt-2';
                        input.parentNode.appendChild(preview);
                    }
                    preview.innerHTML = `
                        <p class="text-muted">الصورة الجديدة:</p>
                        <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Confirm before removing images
    const removeImageCheckboxes = document.querySelectorAll('input[name="remove_tier1_image"], input[name="remove_tier2_image"]');
    removeImageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                Swal.fire({
                    title: 'تأكيد الحذف',
                    text: 'هل أنت متأكد من حذف هذه الصورة؟',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        this.checked = false;
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection

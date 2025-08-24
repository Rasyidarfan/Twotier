@extends('layouts.app')

@section('title', 'إنشاء امتحان جديد')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-plus-circle me-2"></i>
                إنشاء امتحان جديد
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.exams.index') }}">الامتحانات</a></li>
                    <li class="breadcrumb-item active">إنشاء امتحان جديد</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            العودة للامتحانات
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
                    المعلومات الأساسية
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">عنوان الامتحان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">رمز الامتحان</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code') }}" placeholder="سيتم إنشاؤه تلقائياً">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subject_id" class="form-label">المادة <span class="text-danger">*</span></label>
                        <select class="form-select @error('subject_id') is-invalid @enderror" 
                                id="subject_id" name="subject_id" required>
                            <option value="">اختر المادة</option>
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
                        <label for="duration" class="form-label">مدة الامتحان (بالدقائق) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                               id="duration" name="duration" value="{{ old('duration', 60) }}" min="5" max="300" required>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">وصف الامتحان</label>
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
                    إعدادات الامتحان
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="max_attempts" class="form-label">عدد المحاولات المسموحة</label>
                        <select class="form-select @error('max_attempts') is-invalid @enderror" 
                                id="max_attempts" name="max_attempts">
                            <option value="1" {{ old('max_attempts', 1) == 1 ? 'selected' : '' }}>محاولة واحدة</option>
                            <option value="2" {{ old('max_attempts') == 2 ? 'selected' : '' }}>محاولتان</option>
                            <option value="3" {{ old('max_attempts') == 3 ? 'selected' : '' }}>ثلاث محاولات</option>
                            <option value="0" {{ old('max_attempts') == 0 ? 'selected' : '' }}>غير محدود</option>
                        </select>
                        @error('max_attempts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="passing_score" class="form-label">درجة النجاح (%)</label>
                        <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                               id="passing_score" name="passing_score" value="{{ old('passing_score', 60) }}" 
                               min="0" max="100">
                        @error('passing_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="show_results" class="form-label">عرض النتائج</label>
                        <select class="form-select @error('show_results') is-invalid @enderror" 
                                id="show_results" name="show_results">
                            <option value="immediately" {{ old('show_results', 'immediately') == 'immediately' ? 'selected' : '' }}>فوراً بعد الانتهاء</option>
                            <option value="after_exam" {{ old('show_results') == 'after_exam' ? 'selected' : '' }}>بعد انتهاء الامتحان</option>
                            <option value="manual" {{ old('show_results') == 'manual' ? 'selected' : '' }}>يدوياً من قبل المعلم</option>
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
                                   name="shuffle_questions" value="1" {{ old('shuffle_questions') ? 'checked' : '' }}>
                            <label class="form-check-label" for="shuffle_questions">
                                خلط ترتيب الأسئلة
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="shuffle_options" 
                                   name="shuffle_options" value="1" {{ old('shuffle_options') ? 'checked' : '' }}>
                            <label class="form-check-label" for="shuffle_options">
                                خلط ترتيب الخيارات
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="show_correct_answers" 
                                   name="show_correct_answers" value="1" {{ old('show_correct_answers') ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_correct_answers">
                                عرض الإجابات الصحيحة في النتائج
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="prevent_back" 
                                   name="prevent_back" value="1" {{ old('prevent_back') ? 'checked' : '' }}>
                            <label class="form-check-label" for="prevent_back">
                                منع العودة للأسئلة السابقة
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
                    إعدادات التوقيت
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">تاريخ ووقت البدء</label>
                        <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                               id="start_time" name="start_time" value="{{ old('start_time') }}">
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">اتركه فارغاً للبدء الفوري</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label">تاريخ ووقت الانتهاء</label>
                        <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                               id="end_time" name="end_time" value="{{ old('end_time') }}">
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">اتركه فارغاً لعدم تحديد وقت انتهاء</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Selection -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-question-circle me-2"></i>
                    اختيار الأسئلة
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="question_selection_type" class="form-label">طريقة اختيار الأسئلة</label>
                        <select class="form-select" id="question_selection_type" name="question_selection_type">
                            <option value="manual" {{ old('question_selection_type', 'manual') == 'manual' ? 'selected' : '' }}>اختيار يدوي</option>
                            <option value="random" {{ old('question_selection_type') == 'random' ? 'selected' : '' }}>اختيار عشوائي</option>
                            <option value="mixed" {{ old('question_selection_type') == 'mixed' ? 'selected' : '' }}>مختلط</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="total_questions_container" style="display: none;">
                        <label for="total_questions" class="form-label">إجمالي عدد الأسئلة</label>
                        <input type="number" class="form-control" id="total_questions" name="total_questions" 
                               value="{{ old('total_questions', 10) }}" min="1" max="100">
                    </div>
                </div>

                <!-- Manual Question Selection -->
                <div id="manual_selection" class="question-selection-method">
                    <h6 class="text-secondary mb-3">اختيار الأسئلة يدوياً</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="filter_chapter" class="form-label">تصفية حسب الفصل</label>
                            <select class="form-select" id="filter_chapter" name="filter_chapter">
                                <option value="">جميع الفصول</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="filter_difficulty" class="form-label">تصفية حسب الصعوبة</label>
                            <select class="form-select" id="filter_difficulty" name="filter_difficulty">
                                <option value="">جميع المستويات</option>
                                <option value="easy">سهل</option>
                                <option value="medium">متوسط</option>
                                <option value="hard">صعب</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-primary" id="load_questions">
                                    <i class="bi bi-search me-2"></i>
                                    تحميل الأسئلة
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="questions_list" class="mt-3">
                        <!-- Questions will be loaded here -->
                    </div>
                </div>

                <!-- Random Question Selection -->
                <div id="random_selection" class="question-selection-method" style="display: none;">
                    <h6 class="text-secondary mb-3">اختيار الأسئلة عشوائياً</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="easy_questions" class="form-label">أسئلة سهلة</label>
                            <input type="number" class="form-control" id="easy_questions" name="easy_questions" 
                                   value="{{ old('easy_questions', 0) }}" min="0">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="medium_questions" class="form-label">أسئلة متوسطة</label>
                            <input type="number" class="form-control" id="medium_questions" name="medium_questions" 
                                   value="{{ old('medium_questions', 0) }}" min="0">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="hard_questions" class="form-label">أسئلة صعبة</label>
                            <input type="number" class="form-control" id="hard_questions" name="hard_questions" 
                                   value="{{ old('hard_questions', 0) }}" min="0">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">المجموع</label>
                            <input type="text" class="form-control" id="questions_total" readonly>
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
                        إلغاء
                    </a>
                    <div>
                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                            <i class="bi bi-file-earmark me-2"></i>
                            حفظ كمسودة
                        </button>
                        <button type="submit" name="action" value="active" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            إنشاء وتفعيل الامتحان
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
    const questionSelectionType = document.getElementById('question_selection_type');
    const totalQuestionsContainer = document.getElementById('total_questions_container');
    const manualSelection = document.getElementById('manual_selection');
    const randomSelection = document.getElementById('random_selection');
    
    // Handle question selection type change
    questionSelectionType.addEventListener('change', function() {
        const type = this.value;
        
        if (type === 'manual') {
            manualSelection.style.display = 'block';
            randomSelection.style.display = 'none';
            totalQuestionsContainer.style.display = 'none';
        } else if (type === 'random') {
            manualSelection.style.display = 'none';
            randomSelection.style.display = 'block';
            totalQuestionsContainer.style.display = 'block';
        } else if (type === 'mixed') {
            manualSelection.style.display = 'block';
            randomSelection.style.display = 'block';
            totalQuestionsContainer.style.display = 'block';
        }
    });
    
    // Load chapters when subject changes
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        chapterSelect.innerHTML = '<option value="">جميع الفصول</option>';
        
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
        const difficulty = document.getElementById('filter_difficulty').value;
        
        if (!subjectId) {
            Swal.fire({
                title: 'تنبيه!',
                text: 'يرجى اختيار المادة أولاً',
                icon: 'warning',
                confirmButtonText: 'موافق'
            });
            return;
        }
        
        const params = new URLSearchParams({
            subject_id: subjectId,
            ...(chapterId && { chapter_id: chapterId }),
            ...(difficulty && { difficulty: difficulty })
        });
        
        fetch(`/guru/questions/search?${params}`)
            .then(response => response.json())
            .then(questions => {
                displayQuestions(questions);
            })
            .catch(error => {
                console.error('Error loading questions:', error);
                Swal.fire({
                    title: 'خطأ!',
                    text: 'حدث خطأ أثناء تحميل الأسئلة',
                    icon: 'error',
                    confirmButtonText: 'موافق'
                });
            });
    });
    
    // Calculate total questions for random selection
    const questionInputs = ['easy_questions', 'medium_questions', 'hard_questions'];
    questionInputs.forEach(inputId => {
        document.getElementById(inputId).addEventListener('input', calculateTotal);
    });
    
    function calculateTotal() {
        const easy = parseInt(document.getElementById('easy_questions').value) || 0;
        const medium = parseInt(document.getElementById('medium_questions').value) || 0;
        const hard = parseInt(document.getElementById('hard_questions').value) || 0;
        const total = easy + medium + hard;
        
        document.getElementById('questions_total').value = total;
        document.getElementById('total_questions').value = total;
    }
    
    function displayQuestions(questions) {
        const container = document.getElementById('questions_list');
        
        if (questions.length === 0) {
            container.innerHTML = '<p class="text-muted">لم يتم العثور على أسئلة مطابقة للمعايير المحددة.</p>';
            return;
        }
        
        let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr>';
        html += '<th><input type="checkbox" id="select_all_questions"></th>';
        html += '<th>السؤال</th><th>الفصل</th><th>الصعوبة</th><th>النقاط</th>';
        html += '</tr></thead><tbody>';
        
        questions.forEach(question => {
            html += `<tr>
                <td><input type="checkbox" name="selected_questions[]" value="${question.id}" class="question-checkbox"></td>
                <td><div class="text-truncate" style="max-width: 300px;" title="${question.tier1_question}">${question.tier1_question.substring(0, 100)}...</div></td>
                <td><span class="badge bg-info">${question.chapter ? question.chapter.name : 'غير محدد'}</span></td>
                <td><span class="badge bg-${getDifficultyColor(question.difficulty)}">${getDifficultyText(question.difficulty)}</span></td>
                <td>${question.points}</td>
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
            case 'easy': return 'سهل';
            case 'medium': return 'متوسط';
            case 'hard': return 'صعب';
            default: return 'غير محدد';
        }
    }
    
    // Form validation
    document.getElementById('createExamForm').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const subjectId = document.getElementById('subject_id').value;
        const duration = document.getElementById('duration').value;
        const selectionType = document.getElementById('question_selection_type').value;
        
        if (!title || !subjectId || !duration) {
            e.preventDefault();
            Swal.fire({
                title: 'خطأ!',
                text: 'يرجى ملء جميع الحقول المطلوبة',
                icon: 'error',
                confirmButtonText: 'موافق'
            });
            return;
        }
        
        if (selectionType === 'manual') {
            const selectedQuestions = document.querySelectorAll('.question-checkbox:checked');
            if (selectedQuestions.length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: 'تنبيه!',
                    text: 'يرجى اختيار أسئلة للامتحان',
                    icon: 'warning',
                    confirmButtonText: 'موافق'
                });
                return;
            }
        } else if (selectionType === 'random') {
            const total = parseInt(document.getElementById('questions_total').value) || 0;
            if (total === 0) {
                e.preventDefault();
                Swal.fire({
                    title: 'تنبيه!',
                    text: 'يرجى تحديد عدد الأسئلة لكل مستوى صعوبة',
                    icon: 'warning',
                    confirmButtonText: 'موافق'
                });
                return;
            }
        }
    });
    
    // Initialize
    calculateTotal();
});
</script>
@endpush
@endsection

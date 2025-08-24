@extends('layouts.app')

@section('title', 'تعديل الامتحان')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-pencil-square me-2"></i>
                تعديل الامتحان
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.exams.index') }}">الامتحانات</a></li>
                    <li class="breadcrumb-item active">تعديل الامتحان</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>
                العودة للامتحانات
            </a>
            <button type="button" class="btn btn-info" onclick="previewExam()">
                <i class="bi bi-eye me-2"></i>
                معاينة الامتحان
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
                    المعلومات الأساسية
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">عنوان الامتحان <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $exam->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">رمز الامتحان</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $exam->code) }}" readonly>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">لا يمكن تعديل رمز الامتحان</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subject_id" class="form-label">المادة <span class="text-danger">*</span></label>
                        <select class="form-select @error('subject_id') is-invalid @enderror" 
                                id="subject_id" name="subject_id" required>
                            <option value="">اختر المادة</option>
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
                        <label for="duration" class="form-label">مدة الامتحان (بالدقائق) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                               id="duration" name="duration" value="{{ old('duration', $exam->duration) }}" min="5" max="300" required>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">وصف الامتحان</label>
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
                    إعدادات الامتحان
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="max_attempts" class="form-label">عدد المحاولات المسموحة</label>
                        <select class="form-select @error('max_attempts') is-invalid @enderror" 
                                id="max_attempts" name="max_attempts">
                            <option value="1" {{ old('max_attempts', $exam->max_attempts) == 1 ? 'selected' : '' }}>محاولة واحدة</option>
                            <option value="2" {{ old('max_attempts', $exam->max_attempts) == 2 ? 'selected' : '' }}>محاولتان</option>
                            <option value="3" {{ old('max_attempts', $exam->max_attempts) == 3 ? 'selected' : '' }}>ثلاث محاولات</option>
                            <option value="0" {{ old('max_attempts', $exam->max_attempts) == 0 ? 'selected' : '' }}>غير محدود</option>
                        </select>
                        @error('max_attempts')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="passing_score" class="form-label">درجة النجاح (%)</label>
                        <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                               id="passing_score" name="passing_score" value="{{ old('passing_score', $exam->passing_score) }}" 
                               min="0" max="100">
                        @error('passing_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="show_results" class="form-label">عرض النتائج</label>
                        <select class="form-select @error('show_results') is-invalid @enderror" 
                                id="show_results" name="show_results">
                            <option value="immediately" {{ old('show_results', $exam->show_results) == 'immediately' ? 'selected' : '' }}>فوراً بعد الانتهاء</option>
                            <option value="after_exam" {{ old('show_results', $exam->show_results) == 'after_exam' ? 'selected' : '' }}>بعد انتهاء الامتحان</option>
                            <option value="manual" {{ old('show_results', $exam->show_results) == 'manual' ? 'selected' : '' }}>يدوياً من قبل المعلم</option>
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
                                خلط ترتيب الأسئلة
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="shuffle_options" 
                                   name="shuffle_options" value="1" {{ old('shuffle_options', $exam->shuffle_options) ? 'checked' : '' }}>
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
                                   name="show_correct_answers" value="1" {{ old('show_correct_answers', $exam->show_correct_answers) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_correct_answers">
                                عرض الإجابات الصحيحة في النتائج
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="prevent_back" 
                                   name="prevent_back" value="1" {{ old('prevent_back', $exam->prevent_back) ? 'checked' : '' }}>
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
                               id="start_time" name="start_time" 
                               value="{{ old('start_time', $exam->start_time ? $exam->start_time->format('Y-m-d\TH:i') : '') }}">
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">اتركه فارغاً للبدء الفوري</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label">تاريخ ووقت الانتهاء</label>
                        <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                               id="end_time" name="end_time" 
                               value="{{ old('end_time', $exam->end_time ? $exam->end_time->format('Y-m-d\TH:i') : '') }}">
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">اتركه فارغاً لعدم تحديد وقت انتهاء</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Questions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-question-circle me-2"></i>
                    أسئلة الامتحان الحالية ({{ $exam->questions->count() }})
                </h6>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addMoreQuestions()">
                    <i class="bi bi-plus me-2"></i>
                    إضافة أسئلة
                </button>
            </div>
            <div class="card-body">
                @if($exam->questions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>الترتيب</th>
                                    <th>السؤال</th>
                                    <th>الفصل</th>
                                    <th>الصعوبة</th>
                                    <th>النقاط</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="current_questions">
                                @foreach($exam->questions as $index => $question)
                                    <tr data-question-id="{{ $question->id }}">
                                        <td>
                                            <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                            <div class="btn-group-vertical btn-group-sm ms-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        onclick="moveQuestion({{ $question->id }}, 'up')" title="تحريك لأعلى">
                                                    <i class="bi bi-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        onclick="moveQuestion({{ $question->id }}, 'down')" title="تحريك لأسفل">
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
                                            <span class="badge bg-info">{{ $question->chapter->name ?? 'غير محدد' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $question->difficulty == 'easy' ? 'success' : ($question->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                                {{ $question->difficulty == 'easy' ? 'سهل' : ($question->difficulty == 'medium' ? 'متوسط' : 'صعب') }}
                                            </span>
                                        </td>
                                        <td>{{ $question->points }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info" 
                                                        onclick="previewQuestion({{ $question->id }})" title="معاينة">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="removeQuestion({{ $question->id }})" title="إزالة">
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
                        <h5 class="text-gray-600">لا توجد أسئلة</h5>
                        <p class="text-gray-500">لم يتم إضافة أي أسئلة لهذا الامتحان بعد.</p>
                        <button type="button" class="btn btn-primary" onclick="addMoreQuestions()">
                            <i class="bi bi-plus me-2"></i>
                            إضافة أسئلة
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
                    إحصائيات الامتحان
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-primary">{{ $exam->questions->count() }}</h5>
                            <small class="text-muted">إجمالي الأسئلة</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-success">{{ $exam->questions->sum('points') }}</h5>
                            <small class="text-muted">إجمالي النقاط</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-info">{{ $exam->participants_count ?? 0 }}</h5>
                            <small class="text-muted">عدد المشاركين</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="text-warning">{{ $exam->duration }}</h5>
                            <small class="text-muted">المدة (دقيقة)</small>
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
                        @if($exam->status == 'draft')
                            <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                                <i class="bi bi-file-earmark me-2"></i>
                                حفظ كمسودة
                            </button>
                            <button type="submit" name="action" value="active" class="btn btn-success me-2">
                                <i class="bi bi-play me-2"></i>
                                حفظ وتفعيل
                            </button>
                        @endif
                        <button type="submit" name="action" value="update" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            حفظ التعديلات
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
                    إضافة أسئلة للامتحان
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="modal_filter_chapter" class="form-label">تصفية حسب الفصل</label>
                        <select class="form-select" id="modal_filter_chapter">
                            <option value="">جميع الفصول</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="modal_filter_difficulty" class="form-label">تصفية حسب الصعوبة</label>
                        <select class="form-select" id="modal_filter_difficulty">
                            <option value="">جميع المستويات</option>
                            <option value="easy">سهل</option>
                            <option value="medium">متوسط</option>
                            <option value="hard">صعب</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary" id="modal_load_questions">
                                <i class="bi bi-search me-2"></i>
                                تحميل الأسئلة
                            </button>
                        </div>
                    </div>
                </div>
                <div id="modal_questions_list">
                    <!-- Questions will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="addSelectedQuestions()">
                    <i class="bi bi-plus me-2"></i>
                    إضافة الأسئلة المحددة
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
        modalChapterSelect.innerHTML = '<option value="">جميع الفصول</option>';
        
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
            title: 'تنبيه!',
            text: 'يرجى اختيار المادة أولاً',
            icon: 'warning',
            confirmButtonText: 'موافق'
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
                title: 'خطأ!',
                text: 'حدث خطأ أثناء تحميل الأسئلة',
                icon: 'error',
                confirmButtonText: 'موافق'
            });
        });
});

function displayModalQuestions(questions) {
    const container = document.getElementById('modal_questions_list');
    
    if (questions.length === 0) {
        container.innerHTML = '<p class="text-muted">لم يتم العثور على أسئلة مطابقة للمعايير المحددة.</p>';
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr>';
    html += '<th><input type="checkbox" id="modal_select_all_questions"></th>';
    html += '<th>السؤال</th><th>الفصل</th><th>الصعوبة</th><th>النقاط</th>';
    html += '</tr></thead><tbody>';
    
    questions.forEach(question => {
        html += `<tr>
            <td><input type="checkbox" name="modal_selected_questions[]" value="${question.id}" class="modal-question-checkbox"></td>
            <td><div class="text-truncate" style="max-width: 300px;" title="${question.tier1_question}">${question.tier1_question.substring(0, 100)}...</div></td>
            <td><span class="badge bg-info">${question.chapter ? question.chapter.name : 'غير محدد'}</span></td>
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
            title: 'تنبيه!',
            text: 'يرجى اختيار أسئلة لإضافتها',
            icon: 'warning',
            confirmButtonText: 'موافق'
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
                title: 'تم!',
                text: 'تم إضافة الأسئلة بنجاح',
                icon: 'success',
                confirmButtonText: 'موافق'
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'حدث خطأ');
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'خطأ!',
            text: error.message || 'حدث خطأ أثناء إضافة الأسئلة',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
    });
}

function removeQuestion(questionId) {
    Swal.fire({
        title: 'تأكيد الإزالة',
        text: 'هل أنت متأكد من إزالة هذا السؤال من الامتحان؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، أزل',
        cancelButtonText: 'إلغاء'
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
                    throw new Error(data.message || 'حدث خطأ');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'خطأ!',
                    text: error.message || 'حدث خطأ أثناء إزالة السؤال',
                    icon: 'error',
                    confirmButtonText: 'موافق'
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
            throw new Error(data.message || 'حدث خطأ');
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'خطأ!',
            text: error.message || 'حدث خطأ أثناء تحريك السؤال',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
    });
}

function previewQuestion(questionId) {
    fetch(`/guru/questions/${questionId}`)
        .then(response => response.json())
        .then(question => {
            Swal.fire({
                title: 'معاينة السؤال',
                html: `
                    <div class="text-start">
                        <h6>السؤال الأساسي:</h6>
                        <p>${question.tier1_question}</p>
                        <h6>الخيارات:</h6>
                        <ul>
                            <li>أ) ${question.tier1_option_a}</li>
                            <li>ب) ${question.tier1_option_b}</li>
                            <li>ج) ${question.tier1_option_c}</li>
                            <li>د) ${question.tier1_option_d}</li>
                        </ul>
                        <h6>سؤال التبرير:</h6>
                        <p>${question.tier2_question}</p>
                        <h6>خيارات التبرير:</h6>
                        <ul>
                            <li>أ) ${question.tier2_option_a}</li>
                            <li>ب) ${question.tier2_option_b}</li>
                            <li>ج) ${question.tier2_option_c}</li>
                            <li>د) ${question.tier2_option_d}</li>
                        </ul>
                    </div>
                `,
                width: '800px',
                confirmButtonText: 'إغلاق'
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'خطأ!',
                text: 'حدث خطأ أثناء تحميل السؤال',
                icon: 'error',
                confirmButtonText: 'موافق'
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
        case 'easy': return 'سهل';
        case 'medium': return 'متوسط';
        case 'hard': return 'صعب';
        default: return 'غير محدد';
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
            title: 'خطأ!',
            text: 'يرجى ملء جميع الحقول المطلوبة',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
    }
});
</script>
@endpush
@endsection

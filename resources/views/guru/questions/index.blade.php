@extends('layouts.app')

@section('title', 'إدارة الأسئلة')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-question-circle me-2"></i>
                إدارة الأسئلة
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active">الأسئلة</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus me-2"></i>
            إضافة سؤال جديد
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الأسئلة
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                الأسئلة النشطة
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                أسئلة سهلة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $easyQuestions }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-emoji-smile-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                أسئلة صعبة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hardQuestions }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-emoji-frown-fill fa-2x text-gray-300"></i>
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
                البحث والتصفية
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('guru.questions.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="ابحث في نص السؤال...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="subject_id" class="form-label">المادة</label>
                        <select class="form-select" id="subject_id" name="subject_id">
                            <option value="">جميع المواد</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="chapter_id" class="form-label">الفصل</label>
                        <select class="form-select" id="chapter_id" name="chapter_id">
                            <option value="">جميع الفصول</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->id }}" {{ request('chapter_id') == $chapter->id ? 'selected' : '' }}>
                                    {{ $chapter->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="difficulty" class="form-label">الصعوبة</label>
                        <select class="form-select" id="difficulty" name="difficulty">
                            <option value="">جميع المستويات</option>
                            <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>سهل</option>
                            <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>متوسط</option>
                            <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>صعب</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
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
                قائمة الأسئلة
            </h6>
            <div>
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-gear me-2"></i>إجراءات مجمعة
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('activate')">
                            <i class="bi bi-check-circle me-2"></i>تفعيل المحدد
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('deactivate')">
                            <i class="bi bi-x-circle me-2"></i>إلغاء تفعيل المحدد
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')">
                            <i class="bi bi-trash me-2"></i>حذف المحدد
                        </a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-sm btn-success" onclick="exportQuestions()">
                    <i class="bi bi-download me-2"></i>تصدير
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
                                <th>السؤال</th>
                                <th>المادة/الفصل</th>
                                <th>الصعوبة</th>
                                <th>النقاط</th>
                                <th>الحالة</th>
                                <th>الاستخدام</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
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
                                                <strong class="text-primary">المستوى الأول:</strong>
                                                <div class="text-truncate" style="max-width: 300px;" title="{{ $question->tier1_question }}">
                                                    {{ Str::limit($question->tier1_question, 80) }}
                                                </div>
                                            </div>
                                            <div class="tier2-preview">
                                                <strong class="text-success">المستوى الثاني:</strong>
                                                <div class="text-truncate" style="max-width: 300px;" title="{{ $question->tier2_question }}">
                                                    {{ Str::limit($question->tier2_question, 80) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-info mb-1">{{ $question->subject->name ?? 'غير محدد' }}</span>
                                            <br>
                                            <small class="text-muted">{{ $question->chapter->name ?? 'غير محدد' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $question->difficulty == 'easy' ? 'success' : ($question->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                            {{ $question->difficulty == 'easy' ? 'سهل' : ($question->difficulty == 'medium' ? 'متوسط' : 'صعب') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $question->points }}</span>
                                    </td>
                                    <td>
                                        @if($question->status == 'active')
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <small class="text-muted d-block">{{ $question->usage_count ?? 0 }} مرة</small>
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
                                                    onclick="previewQuestion({{ $question->id }})" title="معاينة">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <a href="{{ route('admin.questions.edit', $question->id) }}" 
                                               class="btn btn-sm btn-primary" title="تعديل">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    onclick="duplicateQuestion({{ $question->id }})" title="نسخ">
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
                                                            {{ $question->status == 'active' ? 'إلغاء التفعيل' : 'تفعيل' }}
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item" onclick="viewQuestionStats({{ $question->id }})">
                                                            <i class="bi bi-graph-up me-2"></i>عرض الإحصائيات
                                                        </button>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item text-danger" onclick="deleteQuestion({{ $question->id }})">
                                                            <i class="bi bi-trash me-2"></i>حذف السؤال
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
                    <h5 class="text-gray-600">لا توجد أسئلة</h5>
                    <p class="text-gray-500">لم يتم العثور على أي أسئلة مطابقة للمعايير المحددة.</p>
                    <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus me-2"></i>
                        إضافة سؤال جديد
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
                    معاينة السؤال
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="questionPreviewContent">
                <!-- Question preview will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
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
                    إحصائيات السؤال
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="questionStatsContent">
                <!-- Question stats will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
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
        chapterSelect.innerHTML = '<option value="">جميع الفصول</option>';
        
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
            title: 'تنبيه!',
            text: 'يرجى اختيار أسئلة أولاً',
            icon: 'warning',
            confirmButtonText: 'موافق'
        });
        return;
    }
    
    let title, text, confirmText;
    
    switch(action) {
        case 'activate':
            title = 'تفعيل الأسئلة';
            text = `هل تريد تفعيل ${selectedQuestions.length} سؤال؟`;
            confirmText = 'نعم، فعل';
            break;
        case 'deactivate':
            title = 'إلغاء تفعيل الأسئلة';
            text = `هل تريد إلغاء تفعيل ${selectedQuestions.length} سؤال؟`;
            confirmText = 'نعم، ألغ التفعيل';
            break;
        case 'delete':
            title = 'حذف الأسئلة';
            text = `هل أنت متأكد من حذف ${selectedQuestions.length} سؤال؟ هذا الإجراء لا يمكن التراجع عنه.`;
            confirmText = 'نعم، احذف';
            break;
    }
    
    Swal.fire({
        title: title,
        text: text,
        icon: action === 'delete' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: 'إلغاء',
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
                        title: 'تم!',
                        text: data.message,
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
                    text: error.message || 'حدث خطأ أثناء تنفيذ العملية',
                    icon: 'error',
                    confirmButtonText: 'موافق'
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
                                السؤال الأساسي (المستوى الأول)
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
                                            أ) ${question.tier1_option_a}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier1_correct_answer === 'b' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier1_correct_answer === 'b' ? 'text-success fw-bold' : ''}">
                                            ب) ${question.tier1_option_b}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier1_correct_answer === 'c' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier1_correct_answer === 'c' ? 'text-success fw-bold' : ''}">
                                            ج) ${question.tier1_option_c}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier1_correct_answer === 'd' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier1_correct_answer === 'd' ? 'text-success fw-bold' : ''}">
                                            د) ${question.tier1_option_d}
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
                                سؤال التبرير (المستوى الثاني)
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
                                            أ) ${question.tier2_option_a}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier2_correct_answer === 'b' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier2_correct_answer === 'b' ? 'text-success fw-bold' : ''}">
                                            ب) ${question.tier2_option_b}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier2_correct_answer === 'c' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier2_correct_answer === 'c' ? 'text-success fw-bold' : ''}">
                                            ج) ${question.tier2_option_c}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" disabled ${question.tier2_correct_answer === 'd' ? 'checked' : ''}>
                                        <label class="form-check-label ${question.tier2_correct_answer === 'd' ? 'text-success fw-bold' : ''}">
                                            د) ${question.tier2_option_d}
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
                                معلومات السؤال
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>المادة:</strong> ${question.subject?.name || 'غير محدد'}</p>
                                    <p><strong>الفصل:</strong> ${question.chapter?.name || 'غير محدد'}</p>
                                    <p><strong>الصعوبة:</strong> 
                                        <span class="badge bg-${question.difficulty === 'easy' ? 'success' : (question.difficulty === 'medium' ? 'warning' : 'danger')}">
                                            ${question.difficulty === 'easy' ? 'سهل' : (question.difficulty === 'medium' ? 'متوسط' : 'صعب')}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>النقاط:</strong> ${question.points}</p>
                                    <p><strong>الحالة:</strong> 
                                        <span class="badge bg-${question.status === 'active' ? 'success' : 'secondary'}">
                                            ${question.status === 'active' ? 'نشط' : 'غير نشط'}
                                        </span>
                                    </p>
                                    <p><strong>تاريخ الإنشاء:</strong> ${new Date(question.created_at).toLocaleDateString('ar-SA')}</p>
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
                title: 'خطأ!',
                text: 'حدث خطأ أثناء تحميل السؤال',
                icon: 'error',
                confirmButtonText: 'موافق'
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
                                    <p class="mb-0">مرات المشاهدة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="bi bi-clipboard-check fa-2x text-success mb-2"></i>
                                    <h4 class="text-success">${stats.total_attempts || 0}</h4>
                                    <p class="mb-0">مرات الإجابة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="bi bi-check-circle fa-2x text-info mb-2"></i>
                                    <h4 class="text-info">${stats.correct_answers || 0}</h4>
                                    <p class="mb-0">إجابات صحيحة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="bi bi-percent fa-2x text-warning mb-2"></i>
                                    <h4 class="text-warning">${stats.success_rate || 0}%</h4>
                                    <p class="mb-0">معدل النجاح</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">الاستخدام في الامتحانات</h6>
                                </div>
                                <div class="card-body">
                                    ${stats.exams && stats.exams.length > 0 ? 
                                        stats.exams.map(exam => `
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span>${exam.title}</span>
                                                <small class="text-muted">${new Date(exam.created_at).toLocaleDateString('ar-SA')}</small>
                                            </div>
                                        `).join('') : 
                                        '<p class="text-muted">لم يتم استخدام هذا السؤال في أي امتحان بعد</p>'
                                    }
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">أداء الطلاب</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">المستوى الأول</label>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" style="width: ${stats.tier1_success_rate || 0}%">
                                                ${stats.tier1_success_rate || 0}%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">المستوى الثاني</label>
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
                title: 'خطأ!',
                text: 'حدث خطأ أثناء تحميل إحصائيات السؤال',
                icon: 'error',
                confirmButtonText: 'موافق'
            });
        });
}

function duplicateQuestion(questionId) {
    Swal.fire({
        title: 'نسخ السؤال',
        text: 'هل تريد إنشاء نسخة من هذا السؤال؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم، انسخ',
        cancelButtonText: 'إلغاء'
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
                        title: 'تم!',
                        text: 'تم نسخ السؤال بنجاح',
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
                    text: error.message || 'حدث خطأ أثناء نسخ السؤال',
                    icon: 'error',
                    confirmButtonText: 'موافق'
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
                title: 'تم!',
                text: data.message,
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
            text: error.message || 'حدث خطأ أثناء تغيير حالة السؤال',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
    });
}

function deleteQuestion(questionId) {
    Swal.fire({
        title: 'حذف السؤال',
        text: 'هل أنت متأكد من حذف هذا السؤال؟ هذا الإجراء لا يمكن التراجع عنه.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
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
                        title: 'تم!',
                        text: 'تم حذف السؤال بنجاح',
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
                    text: error.message || 'حدث خطأ أثناء حذف السؤال',
                    icon: 'error',
                    confirmButtonText: 'موافق'
                });
            });
        }
    });
}

function exportQuestions() {
    const selectedQuestions = Array.from(document.querySelectorAll('.question-checkbox:checked')).map(cb => cb.value);
    
    Swal.fire({
        title: 'تصدير الأسئلة',
        text: selectedQuestions.length > 0 ? 
            `هل تريد تصدير ${selectedQuestions.length} سؤال محدد؟` : 
            'هل تريد تصدير جميع الأسئلة؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم، صدر',
        cancelButtonText: 'إلغاء'
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

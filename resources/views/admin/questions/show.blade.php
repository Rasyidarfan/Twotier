@extends('layouts.app')

@section('title', 'عرض السؤال')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-eye me-2"></i>
                عرض السؤال
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">الأسئلة</a></li>
                    <li class="breadcrumb-item active">عرض السؤال</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>
                العودة للقائمة
            </a>
            <a href="{{ route('admin.questions.edit', $question->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>
                تعديل السؤال
            </a>
        </div>
    </div>

    <!-- Question Details -->
    <div class="row">
        <div class="col-12">
            <!-- Question Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>
                        معلومات السؤال
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>المادة:</strong> {{ $question->chapter->subject->name ?? 'غير محدد' }}</p>
                            <p><strong>الفصل:</strong> {{ $question->chapter->name ?? 'غير محدد' }}</p>
                            <p><strong>الصعوبة:</strong> 
                                <span class="badge bg-{{ $question->difficulty === 'easy' ? 'success' : ($question->difficulty === 'medium' ? 'warning' : 'danger') }}">
                                    {{ $question->difficulty === 'easy' ? 'سهل' : ($question->difficulty === 'medium' ? 'متوسط' : 'صعب') }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>النقاط:</strong> {{ $question->points ?? 'غير محدد' }}</p>
                            <p><strong>الحالة:</strong> 
                                <span class="badge bg-{{ $question->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ $question->status === 'active' ? 'نشط' : 'غير نشط' }}
                                </span>
                            </p>
                            <p><strong>تاريخ الإنشاء:</strong> {{ $question->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    @if($question->creator)
                        <p><strong>أنشأ بواسطة:</strong> {{ $question->creator->name }}</p>
                    @endif
                </div>
            </div>

            <!-- Tier 1 Question -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-1-circle me-2"></i>
                        السؤال الأساسي (المستوى الأول)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="question-content mb-4">
                        <h5>نص السؤال:</h5>
                        <div class="p-3 bg-light rounded">
                            {{ $question->tier1_question }}
                        </div>
                    </div>

                    @if($question->tier1_image)
                        <div class="question-image mb-4">
                            <h6>صورة السؤال:</h6>
                            <img src="{{ asset('storage/' . $question->tier1_image) }}" 
                                 class="img-fluid rounded shadow" 
                                 style="max-height: 300px;" 
                                 alt="صورة السؤال">
                        </div>
                    @endif

                    <div class="question-options">
                        <h6>الخيارات:</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" disabled 
                                           {{ $question->tier1_correct_answer === 'a' ? 'checked' : '' }}>
                                    <label class="form-check-label {{ $question->tier1_correct_answer === 'a' ? 'text-success fw-bold' : '' }}">
                                        أ) {{ $question->tier1_option_a }}
                                    </label>
                                    @if($question->tier1_correct_answer === 'a')
                                        <i class="bi bi-check-circle text-success ms-2"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" disabled 
                                           {{ $question->tier1_correct_answer === 'b' ? 'checked' : '' }}>
                                    <label class="form-check-label {{ $question->tier1_correct_answer === 'b' ? 'text-success fw-bold' : '' }}">
                                        ب) {{ $question->tier1_option_b }}
                                    </label>
                                    @if($question->tier1_correct_answer === 'b')
                                        <i class="bi bi-check-circle text-success ms-2"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" disabled 
                                           {{ $question->tier1_correct_answer === 'c' ? 'checked' : '' }}>
                                    <label class="form-check-label {{ $question->tier1_correct_answer === 'c' ? 'text-success fw-bold' : '' }}">
                                        ج) {{ $question->tier1_option_c }}
                                    </label>
                                    @if($question->tier1_correct_answer === 'c')
                                        <i class="bi bi-check-circle text-success ms-2"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" disabled 
                                           {{ $question->tier1_correct_answer === 'd' ? 'checked' : '' }}>
                                    <label class="form-check-label {{ $question->tier1_correct_answer === 'd' ? 'text-success fw-bold' : '' }}">
                                        د) {{ $question->tier1_option_d }}
                                    </label>
                                    @if($question->tier1_correct_answer === 'd')
                                        <i class="bi bi-check-circle text-success ms-2"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tier 2 Question -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-2-circle me-2"></i>
                        سؤال التبرير (المستوى الثاني)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="question-content mb-4">
                        <h5>نص السؤال:</h5>
                        <div class="p-3 bg-light rounded">
                            {{ $question->tier2_question }}
                        </div>
                    </div>

                    @if($question->tier2_image)
                        <div class="question-image mb-4">
                            <h6>صورة السؤال:</h6>
                            <img src="{{ asset('storage/' . $question->tier2_image) }}" 
                                 class="img-fluid rounded shadow" 
                                 style="max-height: 300px;" 
                                 alt="صورة السؤال">
                        </div>
                    @endif

                    <div class="question-options">
                        <h6>الخيارات:</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" disabled 
                                           {{ $question->tier2_correct_answer === 'a' ? 'checked' : '' }}>
                                    <label class="form-check-label {{ $question->tier2_correct_answer === 'a' ? 'text-success fw-bold' : '' }}">
                                        أ) {{ $question->tier2_option_a }}
                                    </label>
                                    @if($question->tier2_correct_answer === 'a')
                                        <i class="bi bi-check-circle text-success ms-2"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" disabled 
                                           {{ $question->tier2_correct_answer === 'b' ? 'checked' : '' }}>
                                    <label class="form-check-label {{ $question->tier2_correct_answer === 'b' ? 'text-success fw-bold' : '' }}">
                                        ب) {{ $question->tier2_option_b }}
                                    </label>
                                    @if($question->tier2_correct_answer === 'b')
                                        <i class="bi bi-check-circle text-success ms-2"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" disabled 
                                           {{ $question->tier2_correct_answer === 'c' ? 'checked' : '' }}>
                                    <label class="form-check-label {{ $question->tier2_correct_answer === 'c' ? 'text-success fw-bold' : '' }}">
                                        ج) {{ $question->tier2_option_c }}
                                    </label>
                                    @if($question->tier2_correct_answer === 'c')
                                        <i class="bi bi-check-circle text-success ms-2"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" disabled 
                                           {{ $question->tier2_correct_answer === 'd' ? 'checked' : '' }}>
                                    <label class="form-check-label {{ $question->tier2_correct_answer === 'd' ? 'text-success fw-bold' : '' }}">
                                        د) {{ $question->tier2_option_d }}
                                    </label>
                                    @if($question->tier2_correct_answer === 'd')
                                        <i class="bi bi-check-circle text-success ms-2"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics (if available) -->
            @if(isset($question->usage_count) && $question->usage_count > 0)
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="bi bi-graph-up me-2"></i>
                            إحصائيات الاستخدام
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-info">{{ $question->usage_count }}</h4>
                                    <p class="mb-0">مرات الاستخدام</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-success">{{ $question->correct_answers ?? 0 }}</h4>
                                    <p class="mb-0">إجابات صحيحة</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-warning">{{ $question->success_rate ?? 0 }}%</h4>
                                    <p class="mb-0">معدل النجاح</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

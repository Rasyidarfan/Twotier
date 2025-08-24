@extends('layouts.app')

@section('title', 'إدارة الامتحانات')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-clipboard-check me-2"></i>
                إدارة الامتحانات
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active">الامتحانات</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('guru.exams.create') }}" class="btn btn-primary">
            <i class="bi bi-plus me-2"></i>
            إنشاء امتحان جديد
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
                                إجمالي الامتحانات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $exams->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-check-fill fa-2x text-gray-300"></i>
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
                                الامتحانات النشطة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $exams->where('status', 'active')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-play-circle-fill fa-2x text-gray-300"></i>
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
                                الامتحانات المكتملة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $exams->where('status', 'completed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle-fill fa-2x text-gray-300"></i>
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
                                إجمالي المشاركين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalParticipants ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('guru.exams.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="ابحث عن امتحان...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="subject" class="form-label">المادة</label>
                        <select class="form-select" id="subject" name="subject">
                            <option value="">جميع المواد</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_range" class="form-label">فترة التاريخ</label>
                        <select class="form-select" id="date_range" name="date_range">
                            <option value="">جميع التواريخ</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>اليوم</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>هذا الشهر</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>
                                بحث
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Exams Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-2"></i>
                قائمة الامتحانات
            </h6>
        </div>
        <div class="card-body">
            @if($exams->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>عنوان الامتحان</th>
                                <th>المادة</th>
                                <th>عدد الأسئلة</th>
                                <th>المدة</th>
                                <th>المشاركين</th>
                                <th>الحالة</th>
                                <th>تاريخ البدء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exams as $exam)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-clipboard-check text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $exam->title }}</h6>
                                                <small class="text-muted">{{ $exam->code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $exam->subject->name ?? 'غير محدد' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $exam->questions_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $exam->duration }} دقيقة
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $exam->participants_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @switch($exam->status)
                                            @case('draft')
                                                <span class="badge bg-secondary">مسودة</span>
                                                @break
                                            @case('active')
                                                <span class="badge bg-success">نشط</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-primary">مكتمل</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">ملغي</span>
                                                @break
                                            @default
                                                <span class="badge bg-light">غير محدد</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($exam->start_time)
                                            {{ $exam->start_time->format('Y-m-d H:i') }}
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    onclick="viewExam({{ $exam->id }})" title="عرض">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            
                                            @if($exam->status == 'draft')
                                                <a href="{{ route('guru.exams.edit', $exam->id) }}" 
                                                   class="btn btn-sm btn-primary" title="تعديل">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            
                                            @if($exam->status == 'active')
                                                <a href="{{ route('guru.exams.waiting-room', $exam->id) }}" 
                                                   class="btn btn-sm btn-success" title="غرفة الانتظار">
                                                    <i class="bi bi-door-open"></i>
                                                </a>
                                            @endif
                                            
                                            @if(in_array($exam->status, ['completed', 'active']))
                                                <a href="{{ route('guru.exams.results', $exam->id) }}" 
                                                   class="btn btn-sm btn-warning" title="النتائج">
                                                    <i class="bi bi-graph-up"></i>
                                                </a>
                                            @endif
                                            
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" 
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($exam->status == 'draft')
                                                        <li>
                                                            <button class="dropdown-item" onclick="activateExam({{ $exam->id }})">
                                                                <i class="bi bi-play me-2"></i>تفعيل الامتحان
                                                            </button>
                                                        </li>
                                                    @endif
                                                    
                                                    @if($exam->status == 'active')
                                                        <li>
                                                            <button class="dropdown-item" onclick="completeExam({{ $exam->id }})">
                                                                <i class="bi bi-check-circle me-2"></i>إنهاء الامتحان
                                                            </button>
                                                        </li>
                                                    @endif
                                                    
                                                    <li>
                                                        <button class="dropdown-item" onclick="duplicateExam({{ $exam->id }})">
                                                            <i class="bi bi-files me-2"></i>نسخ الامتحان
                                                        </button>
                                                    </li>
                                                    
                                                    <li><hr class="dropdown-divider"></li>
                                                    
                                                    @if($exam->status == 'draft')
                                                        <li>
                                                            <button class="dropdown-item text-danger" onclick="deleteExam({{ $exam->id }})">
                                                                <i class="bi bi-trash me-2"></i>حذف الامتحان
                                                            </button>
                                                        </li>
                                                    @endif
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
                @if($exams instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="d-flex justify-content-center">
                        {{ $exams->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="bi bi-clipboard-check fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">لا توجد امتحانات</h5>
                    <p class="text-gray-500">لم يتم العثور على أي امتحانات. ابدأ بإنشاء امتحان جديد.</p>
                    <a href="{{ route('guru.exams.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus me-2"></i>
                        إنشاء امتحان جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- View Exam Modal -->
<div class="modal fade" id="viewExamModal" tabindex="-1" aria-labelledby="viewExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewExamModalLabel">
                    <i class="bi bi-eye me-2"></i>
                    تفاصيل الامتحان
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="examDetails">
                <!-- Exam details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewExam(id) {
    fetch(`/guru/exams/${id}`)
        .then(response => response.json())
        .then(exam => {
            const detailsHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>عنوان الامتحان:</h6>
                        <p>${exam.title}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>رمز الامتحان:</h6>
                        <p>${exam.code}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>المادة:</h6>
                        <p>${exam.subject ? exam.subject.name : 'غير محدد'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>المدة:</h6>
                        <p>${exam.duration} دقيقة</p>
                    </div>
                    <div class="col-12">
                        <h6>الوصف:</h6>
                        <p>${exam.description || 'لا يوجد وصف'}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>عدد الأسئلة:</h6>
                        <p>${exam.questions_count || 0}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>المشاركين:</h6>
                        <p>${exam.participants_count || 0}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>الحالة:</h6>
                        <span class="badge bg-${getStatusColor(exam.status)}">${getStatusText(exam.status)}</span>
                    </div>
                    <div class="col-md-6">
                        <h6>تاريخ البدء:</h6>
                        <p>${exam.start_time ? new Date(exam.start_time).toLocaleString('ar-SA') : 'غير محدد'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>تاريخ الانتهاء:</h6>
                        <p>${exam.end_time ? new Date(exam.end_time).toLocaleString('ar-SA') : 'غير محدد'}</p>
                    </div>
                </div>
            `;
            
            document.getElementById('examDetails').innerHTML = detailsHtml;
            new bootstrap.Modal(document.getElementById('viewExamModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'خطأ!',
                text: 'حدث خطأ أثناء تحميل بيانات الامتحان',
                icon: 'error',
                confirmButtonText: 'موافق'
            });
        });
}

function activateExam(id) {
    Swal.fire({
        title: 'تفعيل الامتحان',
        text: 'هل أنت متأكد من تفعيل هذا الامتحان؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم، فعل',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            updateExamStatus(id, 'active');
        }
    });
}

function completeExam(id) {
    Swal.fire({
        title: 'إنهاء الامتحان',
        text: 'هل أنت متأكد من إنهاء هذا الامتحان؟ لن يتمكن الطلاب من المشاركة بعد ذلك.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، أنهي',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            updateExamStatus(id, 'completed');
        }
    });
}

function deleteExam(id) {
    Swal.fire({
        title: 'تأكيد الحذف',
        text: 'هل أنت متأكد من حذف هذا الامتحان؟ سيتم حذف جميع البيانات المرتبطة به.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/guru/exams/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function duplicateExam(id) {
    Swal.fire({
        title: 'نسخ الامتحان',
        text: 'هل تريد إنشاء نسخة من هذا الامتحان؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم، انسخ',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/guru/exams/${id}/duplicate`, {
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
                        title: 'تم النسخ!',
                        text: 'تم إنشاء نسخة من الامتحان بنجاح',
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
                    text: error.message || 'حدث خطأ أثناء نسخ الامتحان',
                    icon: 'error',
                    confirmButtonText: 'موافق'
                });
            });
        }
    });
}

function updateExamStatus(id, status) {
    fetch(`/guru/exams/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
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
            text: error.message || 'حدث خطأ أثناء تحديث حالة الامتحان',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
    });
}

function getStatusColor(status) {
    switch(status) {
        case 'draft': return 'secondary';
        case 'active': return 'success';
        case 'completed': return 'primary';
        case 'cancelled': return 'danger';
        default: return 'light';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'draft': return 'مسودة';
        case 'active': return 'نشط';
        case 'completed': return 'مكتمل';
        case 'cancelled': return 'ملغي';
        default: return 'غير محدد';
    }
}
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'إدارة المواد')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-book me-2"></i>
                إدارة المواد
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active">المواد</li>
                </ol>
            </nav>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
            <i class="bi bi-plus me-2"></i>
            إضافة مادة جديدة
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي المواد
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subjects->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-book-fill fa-2x text-gray-300"></i>
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
                                المواد النشطة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subjects->where('status', 'active')->count() }}</div>
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
                                إجمالي الفصول
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalChapters ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-collection-fill fa-2x text-gray-300"></i>
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
                                إجمالي الأسئلة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalQuestions ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-question-circle-fill fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('admin.subjects.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="ابحث عن مادة...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sort" class="form-label">ترتيب حسب</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>تاريخ الإنشاء</option>
                            <option value="chapters_count" {{ request('sort') == 'chapters_count' ? 'selected' : '' }}>عدد الفصول</option>
                            <option value="questions_count" {{ request('sort') == 'questions_count' ? 'selected' : '' }}>عدد الأسئلة</option>
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

    <!-- Subjects Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-2"></i>
                قائمة المواد
            </h6>
        </div>
        <div class="card-body">
            @if($subjects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>الرقم</th>
                                <th>اسم المادة</th>
                                <th>الوصف</th>
                                <th>عدد الفصول</th>
                                <th>عدد الأسئلة</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $subject)
                                <tr>
                                    <td>{{ $subject->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-book text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $subject->name }}</h6>
                                                @if($subject->code)
                                                    <small class="text-muted">{{ $subject->code }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subject->description)
                                            <span class="text-truncate" style="max-width: 200px;" title="{{ $subject->description }}">
                                                {{ Str::limit($subject->description, 50) }}
                                            </span>
                                        @else
                                            <span class="text-muted">لا يوجد وصف</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $subject->chapters_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $subject->questions_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @if($subject->status == 'active')
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>{{ $subject->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    onclick="viewSubject({{ $subject->id }})" title="عرض">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    onclick="editSubject({{ $subject->id }})" title="تعديل">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="{{ route('admin.chapters.index', ['subject' => $subject->id]) }}" 
                                               class="btn btn-sm btn-success" title="إدارة الفصول">
                                                <i class="bi bi-collection"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteSubject({{ $subject->id }})" title="حذف">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($subjects instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="d-flex justify-content-center">
                        {{ $subjects->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="bi bi-book fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">لا توجد مواد</h5>
                    <p class="text-gray-500">لم يتم العثور على أي مواد. ابدأ بإضافة مادة جديدة.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                        <i class="bi bi-plus me-2"></i>
                        إضافة مادة جديدة
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Subject Modal -->
<div class="modal fade" id="createSubjectModal" tabindex="-1" aria-labelledby="createSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubjectModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    إضافة مادة جديدة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createSubjectForm" action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم المادة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">رمز المادة</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="مثال: MATH101">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-2"></i>
                        حفظ المادة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Subject Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubjectModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>
                    تعديل المادة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSubjectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">اسم المادة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_code" class="form-label">رمز المادة</label>
                        <input type="text" class="form-control" id="edit_code" name="code">
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">الوصف</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">الحالة</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="active">نشط</option>
                            <option value="inactive">غير نشط</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-2"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Subject Modal -->
<div class="modal fade" id="viewSubjectModal" tabindex="-1" aria-labelledby="viewSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSubjectModalLabel">
                    <i class="bi bi-eye me-2"></i>
                    تفاصيل المادة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="subjectDetails">
                <!-- Subject details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editSubject(id) {
    fetch(`/admin/subjects/${id}`)
        .then(response => response.json())
        .then(subject => {
            document.getElementById('edit_name').value = subject.name;
            document.getElementById('edit_code').value = subject.code || '';
            document.getElementById('edit_description').value = subject.description || '';
            document.getElementById('edit_status').value = subject.status;
            document.getElementById('editSubjectForm').action = `/admin/subjects/${id}`;
            
            new bootstrap.Modal(document.getElementById('editSubjectModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'خطأ!',
                text: 'حدث خطأ أثناء تحميل بيانات المادة',
                icon: 'error',
                confirmButtonText: 'موافق'
            });
        });
}

function viewSubject(id) {
    fetch(`/admin/subjects/${id}`)
        .then(response => response.json())
        .then(subject => {
            const detailsHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>اسم المادة:</h6>
                        <p>${subject.name}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>رمز المادة:</h6>
                        <p>${subject.code || 'غير محدد'}</p>
                    </div>
                    <div class="col-12">
                        <h6>الوصف:</h6>
                        <p>${subject.description || 'لا يوجد وصف'}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>الحالة:</h6>
                        <span class="badge bg-${subject.status === 'active' ? 'success' : 'secondary'}">
                            ${subject.status === 'active' ? 'نشط' : 'غير نشط'}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <h6>عدد الفصول:</h6>
                        <p>${subject.chapters_count || 0}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>عدد الأسئلة:</h6>
                        <p>${subject.questions_count || 0}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>تاريخ الإنشاء:</h6>
                        <p>${new Date(subject.created_at).toLocaleDateString('ar-SA')}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>آخر تحديث:</h6>
                        <p>${new Date(subject.updated_at).toLocaleDateString('ar-SA')}</p>
                    </div>
                </div>
            `;
            
            document.getElementById('subjectDetails').innerHTML = detailsHtml;
            new bootstrap.Modal(document.getElementById('viewSubjectModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'خطأ!',
                text: 'حدث خطأ أثناء تحميل بيانات المادة',
                icon: 'error',
                confirmButtonText: 'موافق'
            });
        });
}

function deleteSubject(id) {
    Swal.fire({
        title: 'تأكيد الحذف',
        text: 'هل أنت متأكد من حذف هذه المادة؟ سيتم حذف جميع الفصول والأسئلة المرتبطة بها.',
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
            form.action = `/admin/subjects/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Form validation
document.getElementById('createSubjectForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    if (!name) {
        e.preventDefault();
        Swal.fire({
            title: 'خطأ!',
            text: 'يرجى إدخال اسم المادة',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
    }
});

document.getElementById('editSubjectForm').addEventListener('submit', function(e) {
    const name = document.getElementById('edit_name').value.trim();
    if (!name) {
        e.preventDefault();
        Swal.fire({
            title: 'خطأ!',
            text: 'يرجى إدخال اسم المادة',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
    }
});
</script>
@endpush
@endsection

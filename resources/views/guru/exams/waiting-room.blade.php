@extends('layouts.app')

@section('title', 'غرفة انتظار الامتحان')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-door-open me-2"></i>
                غرفة انتظار الامتحان
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('guru.exams.index') }}">الامتحانات</a></li>
                    <li class="breadcrumb-item active">غرفة الانتظار</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>
                العودة للامتحانات
            </a>
            <button type="button" class="btn btn-info" onclick="refreshData()">
                <i class="bi bi-arrow-clockwise me-2"></i>
                تحديث البيانات
            </button>
        </div>
    </div>

    <!-- Exam Information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-info-circle me-2"></i>
                معلومات الامتحان
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-primary">{{ $exam->title }}</h5>
                    <p class="text-muted mb-2">{{ $exam->description }}</p>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-book me-2 text-info"></i>
                        <span>{{ $exam->subject->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-clock me-2 text-warning"></i>
                        <span>{{ $exam->duration }} دقيقة</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-question-circle me-2 text-success"></i>
                        <span>{{ $exam->questions->count() }} سؤال</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <h2 class="text-primary">{{ $exam->code }}</h2>
                            <p class="text-muted">رمز الامتحان</p>
                        </div>
                        <div class="mb-3">
                            <span class="badge bg-{{ $exam->status == 'active' ? 'success' : 'secondary' }} fs-6">
                                {{ $exam->status == 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                        @if($exam->status == 'active')
                            <button type="button" class="btn btn-danger" onclick="endExam()">
                                <i class="bi bi-stop-circle me-2"></i>
                                إنهاء الامتحان
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                المشاركون الحاليون
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="current_participants">
                                {{ $currentParticipants }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
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
                                المكتملون
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="completed_participants">
                                {{ $completedParticipants }}
                            </div>
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
                                في الانتظار
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="waiting_participants">
                                {{ $waitingParticipants }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-hourglass-split fa-2x text-gray-300"></i>
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
                                متوسط الدرجات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="average_score">
                                {{ number_format($averageScore, 1) }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Participants -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-broadcast me-2"></i>
                المشاركون المباشرون
            </h6>
            <div>
                <span class="badge bg-success me-2" id="online_indicator">
                    <i class="bi bi-circle-fill"></i>
                    مباشر
                </span>
                <small class="text-muted">آخر تحديث: <span id="last_update">الآن</span></small>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="participants_table">
                    <thead>
                        <tr>
                            <th>الطالب</th>
                            <th>الحالة</th>
                            <th>وقت البدء</th>
                            <th>الوقت المتبقي</th>
                            <th>التقدم</th>
                            <th>النتيجة الحالية</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="participants_tbody">
                        <!-- Participants will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Exam Controls -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-gear me-2"></i>
                التحكم في الامتحان
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>إعدادات الوقت</h6>
                    <div class="mb-3">
                        <label for="extend_time" class="form-label">إضافة وقت إضافي (بالدقائق)</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="extend_time" min="1" max="60" value="5">
                            <button class="btn btn-outline-primary" type="button" onclick="extendTime()">
                                <i class="bi bi-clock me-2"></i>
                                إضافة وقت
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6>إجراءات سريعة</h6>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-warning" onclick="sendMessage()">
                            <i class="bi bi-chat-dots me-2"></i>
                            إرسال رسالة للجميع
                        </button>
                        <button type="button" class="btn btn-info" onclick="exportResults()">
                            <i class="bi bi-download me-2"></i>
                            تصدير النتائج الحالية
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendMessageModalLabel">
                    <i class="bi bi-chat-dots me-2"></i>
                    إرسال رسالة للمشاركين
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="message_type" class="form-label">نوع الرسالة</label>
                    <select class="form-select" id="message_type">
                        <option value="info">معلومات</option>
                        <option value="warning">تحذير</option>
                        <option value="success">نجاح</option>
                        <option value="error">خطأ</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="message_content" class="form-label">محتوى الرسالة</label>
                    <textarea class="form-control" id="message_content" rows="4" placeholder="اكتب رسالتك هنا..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="sendMessageToAll()">
                    <i class="bi bi-send me-2"></i>
                    إرسال الرسالة
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let refreshInterval;
let examId = {{ $exam->id }};

document.addEventListener('DOMContentLoaded', function() {
    // Start auto-refresh
    startAutoRefresh();
    
    // Load initial data
    loadParticipants();
});

function startAutoRefresh() {
    refreshInterval = setInterval(function() {
        loadParticipants();
        updateLastUpdateTime();
    }, 5000); // Refresh every 5 seconds
}

function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
}

function loadParticipants() {
    fetch(`/guru/exams/${examId}/participants`)
        .then(response => response.json())
        .then(data => {
            updateStatistics(data.statistics);
            updateParticipantsTable(data.participants);
        })
        .catch(error => {
            console.error('Error loading participants:', error);
        });
}

function updateStatistics(stats) {
    document.getElementById('current_participants').textContent = stats.current || 0;
    document.getElementById('completed_participants').textContent = stats.completed || 0;
    document.getElementById('waiting_participants').textContent = stats.waiting || 0;
    document.getElementById('average_score').textContent = (stats.average_score || 0).toFixed(1) + '%';
}

function updateParticipantsTable(participants) {
    const tbody = document.getElementById('participants_tbody');
    
    if (participants.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">لا يوجد مشاركون حالياً</td></tr>';
        return;
    }
    
    let html = '';
    participants.forEach(participant => {
        html += `
            <tr class="${getRowClass(participant.status)}">
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                            <i class="bi bi-person text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">${participant.name}</h6>
                            <small class="text-muted">${participant.email}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-${getStatusColor(participant.status)}">
                        ${getStatusText(participant.status)}
                    </span>
                </td>
                <td>
                    ${participant.started_at ? new Date(participant.started_at).toLocaleTimeString('ar-SA') : '-'}
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        ${getTimeRemaining(participant)}
                    </div>
                </td>
                <td>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: ${participant.progress || 0}%" 
                             aria-valuenow="${participant.progress || 0}" 
                             aria-valuemin="0" aria-valuemax="100">
                            ${participant.progress || 0}%
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-info">
                        ${participant.current_score || 0}/${participant.total_score || 0}
                    </span>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-info" 
                                onclick="viewParticipant(${participant.id})" title="عرض التفاصيل">
                            <i class="bi bi-eye"></i>
                        </button>
                        ${participant.status === 'active' ? `
                            <button type="button" class="btn btn-sm btn-warning" 
                                    onclick="sendMessageToParticipant(${participant.id})" title="إرسال رسالة">
                                <i class="bi bi-chat"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    onclick="kickParticipant(${participant.id})" title="طرد من الامتحان">
                                <i class="bi bi-person-x"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
}

function getRowClass(status) {
    switch(status) {
        case 'active': return 'table-success';
        case 'completed': return 'table-info';
        case 'timeout': return 'table-warning';
        case 'kicked': return 'table-danger';
        default: return '';
    }
}

function getStatusColor(status) {
    switch(status) {
        case 'active': return 'success';
        case 'completed': return 'primary';
        case 'waiting': return 'warning';
        case 'timeout': return 'danger';
        case 'kicked': return 'dark';
        default: return 'secondary';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'active': return 'نشط';
        case 'completed': return 'مكتمل';
        case 'waiting': return 'في الانتظار';
        case 'timeout': return 'انتهى الوقت';
        case 'kicked': return 'مطرود';
        default: return 'غير محدد';
    }
}

function getTimeRemaining(participant) {
    if (participant.status !== 'active' || !participant.started_at) {
        return '-';
    }
    
    const startTime = new Date(participant.started_at);
    const duration = {{ $exam->duration }};
    const endTime = new Date(startTime.getTime() + (duration * 60 * 1000));
    const now = new Date();
    const remaining = endTime - now;
    
    if (remaining <= 0) {
        return '<span class="text-danger">انتهى الوقت</span>';
    }
    
    const minutes = Math.floor(remaining / (1000 * 60));
    const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
    
    const color = minutes < 5 ? 'text-danger' : (minutes < 10 ? 'text-warning' : 'text-success');
    
    return `<span class="${color}">${minutes}:${seconds.toString().padStart(2, '0')}</span>`;
}

function updateLastUpdateTime() {
    document.getElementById('last_update').textContent = new Date().toLocaleTimeString('ar-SA');
}

function refreshData() {
    loadParticipants();
    updateLastUpdateTime();
    
    // Show refresh indicator
    const indicator = document.getElementById('online_indicator');
    indicator.innerHTML = '<i class="bi bi-arrow-clockwise"></i> جاري التحديث...';
    indicator.className = 'badge bg-warning me-2';
    
    setTimeout(() => {
        indicator.innerHTML = '<i class="bi bi-circle-fill"></i> مباشر';
        indicator.className = 'badge bg-success me-2';
    }, 1000);
}

function extendTime() {
    const minutes = document.getElementById('extend_time').value;
    
    if (!minutes || minutes < 1) {
        Swal.fire({
            title: 'خطأ!',
            text: 'يرجى إدخال عدد دقائق صحيح',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
        return;
    }
    
    Swal.fire({
        title: 'إضافة وقت إضافي',
        text: `هل تريد إضافة ${minutes} دقيقة إضافية لجميع المشاركين؟`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم، أضف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/guru/exams/${examId}/extend-time`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ minutes: parseInt(minutes) })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'تم!',
                        text: 'تم إضافة الوقت الإضافي بنجاح',
                        icon: 'success',
                        confirmButtonText: 'موافق'
                    });
                    loadParticipants();
                } else {
                    throw new Error(data.message || 'حدث خطأ');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'خطأ!',
                    text: error.message || 'حدث خطأ أثناء إضافة الوقت',
                    icon: 'error',
                    confirmButtonText: 'موافق'
                });
            });
        }
    });
}

function sendMessage() {
    new bootstrap.Modal(document.getElementById('sendMessageModal')).show();
}

function sendMessageToAll() {
    const type = document.getElementById('message_type').value;
    const content = document.getElementById('message_content').value.trim();
    
    if (!content) {
        Swal.fire({
            title: 'خطأ!',
            text: 'يرجى كتابة محتوى الرسالة',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
        return;
    }
    
    fetch(`/guru/exams/${examId}/broadcast-message`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ type: type, content: content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('sendMessageModal')).hide();
            Swal.fire({
                title: 'تم!',
                text: 'تم إرسال الرسالة لجميع المشاركين',
                icon: 'success',
                confirmButtonText: 'موافق'
            });
            document.getElementById('message_content').value = '';
        } else {
            throw new Error(data.message || 'حدث خطأ');
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'خطأ!',
            text: error.message || 'حدث خطأ أثناء إرسال الرسالة',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
    });
}

function endExam() {
    Swal.fire({
        title: 'إنهاء الامتحان',
        text: 'هل أنت متأكد من إنهاء الامتحان؟ سيتم إنهاء جميع المحاولات الجارية.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'نعم، أنهي الامتحان',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/guru/exams/${examId}/end`, {
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
                        text: 'تم إنهاء الامتحان بنجاح',
                        icon: 'success',
                        confirmButtonText: 'موافق'
                    }).then(() => {
                        window.location.href = `/guru/exams/${examId}/results`;
                    });
                } else {
                    throw new Error(data.message || 'حدث خطأ');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'خطأ!',
                    text: error.message || 'حدث خطأ أثناء إنهاء الامتحان',
                    icon: 'error',
                    confirmButtonText: 'موافق'
                });
            });
        }
    });
}

function exportResults() {
    window.open(`/guru/exams/${examId}/export-current-results`, '_blank');
}

function viewParticipant(participantId) {
    window.open(`/guru/exams/${examId}/participants/${participantId}`, '_blank');
}

function kickParticipant(participantId) {
    Swal.fire({
        title: 'طرد المشارك',
        text: 'هل أنت متأكد من طرد هذا المشارك من الامتحان؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'نعم، اطرد',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/guru/exams/${examId}/participants/${participantId}/kick`, {
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
                        text: 'تم طرد المشارك من الامتحان',
                        icon: 'success',
                        confirmButtonText: 'موافق'
                    });
                    loadParticipants();
                } else {
                    throw new Error(data.message || 'حدث خطأ');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'خطأ!',
                    text: error.message || 'حدث خطأ أثناء طرد المشارك',
                    icon: 'error',
                    confirmButtonText: 'موافق'
                });
            });
        }
    });
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    stopAutoRefresh();
});
</script>
@endpush
@endsection

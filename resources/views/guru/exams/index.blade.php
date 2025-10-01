@extends('layouts.app')

@section('title', 'Kelola Ujian')

@push('styles')
<style>
    .card-body.p-0 {
        overflow: visible !important;
    }
    .table-responsive {
        overflow-x: auto;
        overflow-y: visible;
    }
    .dropdown-menu {
        z-index: 1050;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Kelola Ujian</h1>
                    <p class="text-muted">Buat, edit, dan pantau ujian Anda</p>
                </div>
                <div>
                    <a href="{{ route('guru.exams.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Buat Ujian Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $exams->total() }}</h4>
                            <p class="mb-0">Total Ujian</p>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-file-earmark-text" style="font-size: 2rem; opacity: 0.75;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $exams->filter(fn($exam) => $exam->status === 'active')->count() }}</h4>
                            <p class="mb-0">Ujian Aktif</p>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-play-fill" style="font-size: 2rem; opacity: 0.75;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $exams->filter(fn($exam) => $exam->status === 'waiting')->count() }}</h4>
                            <p class="mb-0">Menunggu</p>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-clock" style="font-size: 2rem; opacity: 0.75;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-0">{{ $exams->filter(fn($exam) => $exam->status === 'draft')->count() }}</h4>
                            <p class="mb-0">Draft</p>
                        </div>
                        <div class="ms-3">
                            <i class="bi bi-pencil-square" style="font-size: 2rem; opacity: 0.75;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" id="filter-form">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" onchange="document.getElementById('filter-form').submit();">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="waiting" {{ request('status') === 'waiting' ? 'selected' : '' }}>Waiting</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>Finished</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mata Pelajaran</label>
                                <select name="subject" class="form-select" onchange="document.getElementById('filter-form').submit();">
                                    <option value="">Semua Mata Pelajaran</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cari</label>
                                <input type="text" name="search" class="form-control" placeholder="Cari judul ujian..." 
                                       value="{{ request('search') }}" onchange="document.getElementById('filter-form').submit();">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                    <i class="bi bi-x-lg"></i> Clear
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Exams List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    @if($exams->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Judul Ujian</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas/Semester</th>
                                        <th>Durasi</th>
                                        <th>Soal</th>
                                        <th>Status</th>
                                        <th>Kode Ujian</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exams as $index => $exam)
                                        <tr>
                                            <td>{{ $exams->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $exam->title }}</strong>
                                                @if($exam->description)
                                                    <br><small class="text-muted">{{ Str::limit($exam->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $exam->subject->name ?? '-' }}</td>
                                            <td>{{ $exam->grade }} / {{ ucfirst($exam->semester) }}</td>
                                            <td>{{ $exam->duration_minutes }} menit</td>
                                            <td>
                                                <span class="badge bg-info">{{ $exam->exam_questions_count }} soal</span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClasses = [
                                                        'draft' => 'secondary',
                                                        'waiting' => 'warning',
                                                        'active' => 'success',
                                                        'finished' => 'primary'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusClasses[$exam->status] ?? 'secondary' }}">
                                                    {{ strtoupper($exam->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <code class="small">{{ $exam->code }}</code>
                                                    @if($exam->status === 'waiting' && $exam->current_code)
                                                        <code class="small text-success" title="Kode aktif saat ini">{{ $exam->current_code }}</code>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $exam->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewExamDetails({{ $exam->id }})" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </button>

                                                    @if($exam->status === 'draft')
                                                        <a href="{{ route('guru.exams.edit', $exam) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-info mx-1" onclick="updateExamStatus({{ $exam->id }}, 'waiting')" title="Set Waiting">
                                                            <i class="bi bi-clock"> Set Waiting</i>
                                                        </button>
                                                    @endif

                                                    @if($exam->status === 'waiting')
                                                        <a href="{{ route('guru.exams.waiting-room', $exam) }}" class="btn btn-sm btn-outline-success" title="Monitor">
                                                            <i class="bi bi-display"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-success mx-1" onclick="updateExamStatus({{ $exam->id }}, 'active')" title="Mulai Ujian">
                                                            <i class="bi bi-play-fill"> Mulai Ujian</i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-secondary" onclick="updateExamStatus({{ $exam->id }}, 'draft')" title="Kembali ke Draft">
                                                            <i class="bi bi-arrow-left"></i>
                                                        </button>
                                                    @endif

                                                    @if($exam->status === 'active')
                                                        <a href="{{ route('guru.exams.waiting-room', $exam) }}" class="btn btn-sm btn-outline-success" title="Monitor">
                                                            <i class="bi bi-display"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-outline-danger" onclick="updateExamStatus({{ $exam->id }}, 'finished')" title="Akhiri Ujian">
                                                            <i class="bi bi-stop-fill"></i>
                                                        </button>
                                                    @endif

                                                    @if($exam->status === 'finished')
                                                        <a href="{{ route('guru.exams.results', $exam) }}" class="btn btn-sm btn-outline-info" title="Hasil">
                                                            <i class="bi bi-bar-chart"></i>
                                                        </a>
                                                    @endif

                                                    <button class="btn btn-sm btn-outline-secondary" onclick="duplicateExam({{ $exam->id }})" title="Duplikat">
                                                        <i class="bi bi-files"></i>
                                                    </button>

                                                    @if(in_array($exam->status, ['draft', 'finished']))
                                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteExam({{ $exam->id }}, '{{ addslashes($exam->title) }}', '{{ $exam->status }}')" title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="p-3">
                            {{ $exams->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-text text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5>Belum Ada Ujian</h5>
                            <p class="text-muted">Mulai buat ujian pertama Anda dengan mengklik tombol "Buat Ujian Baru"</p>
                            <a href="{{ route('guru.exams.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Buat Ujian Baru
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exam Details Modal -->
<div class="modal fade" id="examDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Ujian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="examDetailsContent">
                <!-- Will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function clearFilters() {
    const url = new URL(window.location);
    url.search = '';
    window.location.href = url.toString();
}

function viewExamDetails(examId) {
    fetch(`/guru/exams/${examId}`)
        .then(response => response.json())
        .then(data => {
            const content = `
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr><th>Judul:</th><td>${data.title}</td></tr>
                            <tr><th>Mata Pelajaran:</th><td>${data.subject?.name || '-'}</td></tr>
                            <tr><th>Durasi:</th><td>${data.duration} menit</td></tr>
                            <tr><th>Status:</th><td><span class="badge bg-primary">${data.status.toUpperCase()}</span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr><th>Kode Ujian:</th><td><code>${data.code}</code></td></tr>
                            ${data.current_code ? `<tr><th>Kode Aktif:</th><td><code class="text-success">${data.current_code}</code></td></tr>` : ''}
                            <tr><th>Jumlah Soal:</th><td>${data.questions_count}</td></tr>
                            <tr><th>Peserta:</th><td>${data.participants_count}</td></tr>
                            <tr><th>Acak Soal:</th><td>${data.shuffle_questions ? 'Ya' : 'Tidak'}</td></tr>
                            <tr><th>Tampilkan Hasil:</th><td>${data.show_result_immediately ? 'Ya' : 'Tidak'}</td></tr>
                        </table>
                    </div>
                </div>
                ${data.description ? `<div class="mt-3"><strong>Deskripsi:</strong><br>${data.description}</div>` : ''}
            `;

            document.getElementById('examDetailsContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('examDetailsModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Gagal memuat detail ujian',
                icon: 'error'
            });
        });
}

function updateExamStatus(examId, status) {
    const messages = {
        'draft': 'mengatur ujian ke status draft',
        'waiting': 'mengatur ujian ke status waiting',
        'active': 'memulai ujian',
        'finished': 'mengakhiri ujian'
    };

    const icons = {
        'draft': 'question',
        'waiting': 'info',
        'active': 'success',
        'finished': 'warning'
    };

    Swal.fire({
        title: 'Konfirmasi',
        text: `Apakah Anda yakin ingin ${messages[status]}?`,
        icon: icons[status],
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/guru/exams/${examId}/status`, {
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
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan',
                    icon: 'error'
                });
            });
        }
    });
}

function duplicateExam(examId) {
    Swal.fire({
        title: 'Duplikat Ujian',
        text: 'Apakah Anda yakin ingin menduplikat ujian ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Duplikat',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/guru/exams/${examId}/duplicate`, {
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
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan',
                    icon: 'error'
                });
            });
        }
    });
}

function deleteExam(examId, examTitle, examStatus) {
    let warningMessage = '';
    let confirmText = 'Ya, Hapus';
    
    if (examStatus === 'finished') {
        warningMessage = `
            <div class="alert alert-danger mt-3 mb-0 text-start">
                <i class="bi bi-exclamation-triangle-fill"></i> 
                <strong>Perhatian!</strong><br>
                Ujian ini sudah selesai dan memiliki data hasil ujian.<br>
                Menghapus ujian akan menghapus:<br>
                • Semua jawaban siswa<br>
                • Semua sesi ujian<br>
                • Semua hasil dan nilai<br>
                • Data statistik ujian
            </div>
        `;
        confirmText = 'Ya, Hapus Semua Data';
    }
    
    Swal.fire({
        title: 'Hapus Ujian',
        html: `
            Apakah Anda yakin ingin menghapus ujian <strong>"${examTitle}"</strong>?
            <br><small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>
            ${warningMessage}
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        width: examStatus === 'finished' ? '600px' : '400px'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading indicator for finished exams
            if (examStatus === 'finished') {
                Swal.fire({
                    title: 'Menghapus Data...',
                    text: 'Sedang menghapus ujian dan semua data terkait',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
            
            fetch(`/guru/exams/${examId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: 'Terhapus!',
                    text: examStatus === 'finished' ? 'Ujian dan semua hasil ujian berhasil dihapus' : 'Ujian berhasil dihapus',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan saat menghapus ujian',
                    icon: 'error'
                });
            });
        }
    });
}
</script>
@endpush
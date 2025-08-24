@extends('layouts.app')

@section('title', 'Kelola Bab')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-collection me-2"></i>
                Kelola Bab
                @if(request('subject'))
                    <small class="text-muted">- {{ $currentSubject->name ?? 'Mata Pelajaran Tidak Ditentukan' }}</small>
                @endif
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.subjects.index') }}">Mata Pelajaran</a></li>
                    <li class="breadcrumb-item active">Bab</li>
                </ol>
            </nav>
        </div>
        <div>
            @if(request('subject'))
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left me-2"></i>
                    Kembali ke Mata Pelajaran
                </a>
            @endif
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createChapterModal">
                <i class="bi bi-plus me-2"></i>
                Tambah Bab Baru
            </button>
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
                                Total Bab
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $chapters->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-collection-fill fa-2x text-gray-300"></i>
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
                                Bab Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $chapters->where('status', 'active')->count() }}</div>
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
                                Total Soal
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Rata-rata Soal/Bab
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $chapters->count() > 0 ? round(($totalQuestions ?? 0) / $chapters->count(), 1) : 0 }}
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

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-funnel me-2"></i>
                Pencarian dan Filter
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.chapters.index') }}">
                @if(request('subject'))
                    <input type="hidden" name="subject" value="{{ request('subject') }}">
                @endif
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Cari bab...">
                    </div>
                    @if(!request('subject'))
                        <div class="col-md-3 mb-3">
                            <label for="subject_filter" class="form-label">Mata Pelajaran</label>
                            <select class="form-select" id="subject_filter" name="subject_filter">
                                <option value="">Semua Mata Pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_filter') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="sort" class="form-label">Urutkan berdasarkan</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="order" {{ request('sort') == 'order' ? 'selected' : '' }}>Urutan</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            <option value="questions_count" {{ request('sort') == 'questions_count' ? 'selected' : '' }}>Jumlah Soal</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Chapters Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-2"></i>
                Daftar Bab
            </h6>
        </div>
        <div class="card-body">
            @if($chapters->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Nama Bab</th>
                                @if(!request('subject'))
                                    <th>Mata Pelajaran</th>
                                @endif
                                <th>Deskripsi</th>
                                <th>Jumlah Soal</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="chaptersTableBody">
                            @foreach($chapters as $chapter)
                                <tr data-chapter-id="{{ $chapter->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-secondary me-2">{{ $chapter->order ?? 0 }}</span>
                                            <div class="btn-group-vertical btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        onclick="moveChapter({{ $chapter->id }}, 'up')" title="Naik">
                                                    <i class="bi bi-arrow-up"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                                        onclick="moveChapter({{ $chapter->id }}, 'down')" title="Turun">
                                                    <i class="bi bi-arrow-down"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-collection text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $chapter->name }}</h6>
                                                @if($chapter->code)
                                                    <small class="text-muted">{{ $chapter->code }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @if(!request('subject'))
                                        <td>
                                            <span class="badge bg-primary">{{ $chapter->subject->name ?? 'Tidak Ditentukan' }}</span>
                                        </td>
                                    @endif
                                    <td>
                                        @if($chapter->description)
                                            <span class="text-truncate" style="max-width: 200px;" title="{{ $chapter->description }}">
                                                {{ Str::limit($chapter->description, 50) }}
                                            </span>
                                        @else
                                            <span class="text-muted">Tidak ada deskripsi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $chapter->questions_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @if($chapter->status == 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $chapter->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    onclick="viewChapter({{ $chapter->id }})" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    onclick="editChapter({{ $chapter->id }})" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="{{ route('admin.questions.index', ['chapter' => $chapter->id]) }}" 
                                               class="btn btn-sm btn-success" title="Kelola Soal">
                                                <i class="bi bi-question-circle"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteChapter({{ $chapter->id }})" title="Hapus">
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
                @if($chapters instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="d-flex justify-content-center">
                        {{ $chapters->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="bi bi-collection fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">Tidak Ada Bab</h5>
                    <p class="text-gray-500">Tidak ditemukan bab apapun. Mulai dengan menambahkan bab baru.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createChapterModal">
                        <i class="bi bi-plus me-2"></i>
                        Tambah Bab Baru
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Chapter Modal -->
<div class="modal fade" id="createChapterModal" tabindex="-1" aria-labelledby="createChapterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createChapterModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>
                    Tambah Bab Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createChapterForm" action="{{ route('admin.chapters.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select" id="subject_id" name="subject_id" required>
                            @if(request('subject'))
                                <option value="{{ request('subject') }}" selected>{{ $currentSubject->name ?? 'Mata Pelajaran Dipilih' }}</option>
                            @else
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Bab <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Bab</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Contoh: BAB01">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order" class="form-label">Urutan</label>
                                <input type="number" class="form-control" id="order" name="order" min="1" value="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-2"></i>
                        Simpan Bab
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Chapter Modal -->
<div class="modal fade" id="editChapterModal" tabindex="-1" aria-labelledby="editChapterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editChapterModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>
                    Edit Bab
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editChapterForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_subject_id" name="subject_id" required>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Bab <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_code" class="form-label">Kode Bab</label>
                        <input type="text" class="form-control" id="edit_code" name="code">
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_order" class="form-label">Urutan</label>
                                <input type="number" class="form-control" id="edit_order" name="order" min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status" required>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Chapter Modal -->
<div class="modal fade" id="viewChapterModal" tabindex="-1" aria-labelledby="viewChapterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewChapterModalLabel">
                    <i class="bi bi-eye me-2"></i>
                    Detail Bab
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="chapterDetails">
                <!-- Chapter details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editChapter(id) {
    fetch(`/admin/chapters/${id}`)
        .then(response => response.json())
        .then(chapter => {
            document.getElementById('edit_subject_id').value = chapter.subject_id;
            document.getElementById('edit_name').value = chapter.name;
            document.getElementById('edit_code').value = chapter.code || '';
            document.getElementById('edit_description').value = chapter.description || '';
            document.getElementById('edit_order').value = chapter.order || 1;
            document.getElementById('edit_status').value = chapter.status;
            document.getElementById('editChapterForm').action = `/admin/chapters/${id}`;
            
            new bootstrap.Modal(document.getElementById('editChapterModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat data bab',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
}

function viewChapter(id) {
    fetch(`/admin/chapters/${id}`)
        .then(response => response.json())
        .then(chapter => {
            const detailsHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Nama Bab:</h6>
                        <p>${chapter.name}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Mata Pelajaran:</h6>
                        <p>${chapter.subject ? chapter.subject.name : 'Tidak Ditentukan'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Kode Bab:</h6>
                        <p>${chapter.code || 'Tidak Ditentukan'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Urutan:</h6>
                        <p>${chapter.order || 0}</p>
                    </div>
                    <div class="col-12">
                        <h6>Deskripsi:</h6>
                        <p>${chapter.description || 'Tidak ada deskripsi'}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Status:</h6>
                        <span class="badge bg-${chapter.status === 'active' ? 'success' : 'secondary'}">
                            ${chapter.status === 'active' ? 'Aktif' : 'Tidak Aktif'}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <h6>Jumlah Soal:</h6>
                        <p>${chapter.questions_count || 0}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Terakhir Diperbarui:</h6>
                        <p>${new Date(chapter.updated_at).toLocaleDateString('id-ID')}</p>
                    </div>
                </div>
            `;
            
            document.getElementById('chapterDetails').innerHTML = detailsHtml;
            new bootstrap.Modal(document.getElementById('viewChapterModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat data bab',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
}

function deleteChapter(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus bab ini? Semua soal yang terkait akan ikut terhapus.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/chapters/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function moveChapter(id, direction) {
    fetch(`/admin/chapters/${id}/move`, {
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
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Terjadi kesalahan saat memindahkan bab',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat memindahkan bab',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

// Form validation
document.getElementById('createChapterForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const subjectId = document.getElementById('subject_id').value;
    
    if (!name || !subjectId) {
        e.preventDefault();
        Swal.fire({
            title: 'Error!',
            text: 'Silakan isi semua field yang diperlukan',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
});

document.getElementById('editChapterForm').addEventListener('submit', function(e) {
    const name = document.getElementById('edit_name').value.trim();
    const subjectId = document.getElementById('edit_subject_id').value;
    
    if (!name || !subjectId) {
        e.preventDefault();
        Swal.fire({
            title: 'Error!',
            text: 'Silakan isi semua field yang diperlukan',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
});
</script>
@endpush
@endsection

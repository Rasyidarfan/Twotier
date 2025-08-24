@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-book me-2"></i>
                Kelola Mata Pelajaran
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mata Pelajaran</li>
                </ol>
            </nav>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
            <i class="bi bi-plus me-2"></i>
            Tambah Mata Pelajaran Baru
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
                                Total Mata Pelajaran
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
                                Mata Pelajaran Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subjects->where('is_active', true)->count() }}</div>
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
                                Total Bab
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subjects->sum('chapters_count') ?? 0 }}</div>
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
                                Total Soal
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Question::count() ?? 0 }}</div>
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
                Pencarian dan Filter
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.subjects.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Cari mata pelajaran...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sort" class="form-label">Urutkan berdasarkan</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            <option value="chapters_count" {{ request('sort') == 'chapters_count' ? 'selected' : '' }}>Jumlah Bab</option>
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

    <!-- Subjects Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-table me-2"></i>
                Daftar Mata Pelajaran
            </h6>
        </div>
        <div class="card-body">
            @if($subjects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mata Pelajaran</th>
                                <th>Kode</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Bab</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $index => $subject)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-book text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $subject->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($subject->code)
                                            <span class="badge bg-secondary">{{ $subject->code }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subject->description)
                                            <span class="text-truncate" style="max-width: 200px;" title="{{ $subject->description }}">
                                                {{ Str::limit($subject->description, 50) }}
                                            </span>
                                        @else
                                            <span class="text-muted">Tidak ada deskripsi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $subject->chapters_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @if($subject->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $subject->created_at ? $subject->created_at->format('d M Y') : '-' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    onclick="viewSubject({{ $subject->id }})" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    onclick="editSubject({{ $subject->id }})" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="{{ route('admin.chapters.index', ['subject' => $subject->id]) }}" 
                                               class="btn btn-sm btn-warning" title="Kelola Bab">
                                                <i class="bi bi-collection"></i>
                                            </a>
                                            <a href="{{ route('admin.questions.index', ['subject' => $subject->id]) }}" 
                                               class="btn btn-sm btn-success" title="Kelola Soal">
                                                <i class="bi bi-question-circle"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteSubject({{ $subject->id }})" title="Hapus">
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
                    <h5 class="text-gray-600">Belum Ada Mata Pelajaran</h5>
                    <p class="text-gray-500">Belum ada mata pelajaran yang ditambahkan. Mulai dengan menambahkan mata pelajaran baru.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                        <i class="bi bi-plus me-2"></i>
                        Tambah Mata Pelajaran Baru
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
                    Tambah Mata Pelajaran Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createSubjectForm" action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Contoh: Bahasa Arab">
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Mata Pelajaran</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Contoh: ARAB01">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi singkat mata pelajaran..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-2"></i>
                        Simpan Mata Pelajaran
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
                    Edit Mata Pelajaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSubjectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_code" class="form-label">Kode Mata Pelajaran</label>
                        <input type="text" class="form-control" id="edit_code" name="code">
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_is_active" class="form-label">Status</label>
                        <select class="form-select" id="edit_is_active" name="is_active" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
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

<!-- View Subject Modal -->
<div class="modal fade" id="viewSubjectModal" tabindex="-1" aria-labelledby="viewSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSubjectModalLabel">
                    <i class="bi bi-eye me-2"></i>
                    Detail Mata Pelajaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="subjectDetails">
                <!-- Subject details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
            document.getElementById('edit_is_active').value = subject.is_active ? '1' : '0';
            document.getElementById('editSubjectForm').action = `/admin/subjects/${id}`;
            
            new bootstrap.Modal(document.getElementById('editSubjectModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat data mata pelajaran',
                icon: 'error',
                confirmButtonText: 'OK'
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
                        <h6>Nama Mata Pelajaran:</h6>
                        <p>${subject.name}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Kode Mata Pelajaran:</h6>
                        <p>${subject.code || 'Tidak ada kode'}</p>
                    </div>
                    <div class="col-12">
                        <h6>Deskripsi:</h6>
                        <p>${subject.description || 'Tidak ada deskripsi'}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Status:</h6>
                        <span class="badge bg-${subject.is_active ? 'success' : 'secondary'}">
                            ${subject.is_active ? 'Aktif' : 'Tidak Aktif'}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <h6>Jumlah Bab:</h6>
                        <p>${subject.chapters_count || 0}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Jumlah Soal:</h6>
                        <p>0</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Tanggal Dibuat:</h6>
                        <p>${new Date(subject.created_at).toLocaleDateString('id-ID')}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Terakhir Diupdate:</h6>
                        <p>${new Date(subject.updated_at).toLocaleDateString('id-ID')}</p>
                    </div>
                </div>
            `;
            
            document.getElementById('subjectDetails').innerHTML = detailsHtml;
            new bootstrap.Modal(document.getElementById('viewSubjectModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memuat data mata pelajaran',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
}

function deleteSubject(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus mata pelajaran ini? Semua bab dan soal terkait akan ikut terhapus.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
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
            title: 'Peringatan!',
            text: 'Nama mata pelajaran wajib diisi',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    }
});

document.getElementById('editSubjectForm').addEventListener('submit', function(e) {
    const name = document.getElementById('edit_name').value.trim();
    if (!name) {
        e.preventDefault();
        Swal.fire({
            title: 'Peringatan!',
            text: 'Nama mata pelajaran wajib diisi',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    }
});

// Success/Error messages
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endpush
@endsection
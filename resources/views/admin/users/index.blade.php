@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
    }

    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #6777ef;
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-2px);
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        background: #e3eaff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6777ef;
        font-size: 1.5rem;
    }

    .user-avatar {
        width: 20px;
        height: 20px;
        background: linear-gradient(135deg, #6777ef, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        font-weight: 300;
        margin: 0 auto 1rem;
    }

    .table-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-header {
        background: #f8f9fa;
        padding: 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 6px;
        margin: 0 2px;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .role-admin {
        background: #d1ecf1;
        color: #0c5460;
    }

    .role-guru {
        background: #d4edda;
        color: #155724;
    }

    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .search-box input {
        padding-left: 45px;
        border-radius: 25px;
        border: 1px solid #dee2e6;
    }

    .filter-tabs {
        border-bottom: 2px solid #f8f9fa;
        margin-bottom: 1rem;
    }

    .filter-tab {
        background: none;
        border: none;
        padding: 0.75rem 1.5rem;
        color: #6c757d;
        font-weight: 500;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }

    .filter-tab.active {
        color: #6777ef;
        border-bottom-color: #6777ef;
    }

    .filter-tab:hover {
        color: #6777ef;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-0">
                    <i class="bi bi-people"></i>
                    Kelola Pengguna
                </h1>
                <p class="mb-0 opacity-75">Manajemen akun admin dan guru</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.users.create') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-plus"></i>
                    Tambah Pengguna
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $users->total() }}</h3>
                        <p class="text-muted mb-0">Total Pengguna</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $users->where('role', 'admin')->count() }}</h3>
                        <p class="text-muted mb-0">Admin</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="bi bi-person-video3"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $users->where('role', 'guru')->count() }}</h3>
                        <p class="text-muted mb-0">Guru</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-card">
        <div class="table-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Daftar Pengguna</h5>
                </div>
                <div class="col-md-6">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control" placeholder="Cari pengguna..." id="searchInput">
                    </div>
                </div>
            </div>
            
            <!-- Filter Tabs -->
            <div class="filter-tabs mt-3">
                <button class="filter-tab active" data-filter="all">
                    <i class="bi bi-list"></i>
                    Semua ({{ $users->total() }})
                </button>
                <button class="filter-tab" data-filter="admin">
                    <i class="bi bi-shield-check"></i>
                    Admin ({{ $users->where('role', 'admin')->count() }})
                </button>
                <button class="filter-tab" data-filter="guru">
                    <i class="bi bi-person-video3"></i>
                    Guru ({{ $users->where('role', 'guru')->count() }})
                </button>
                <button class="filter-tab" data-filter="active">
                    <i class="bi bi-check-circle"></i>
                    Aktif ({{ $users->where('is_active', true)->count() }})
                </button>
                <button class="filter-tab" data-filter="inactive">
                    <i class="bi bi-x-circle"></i>
                    Nonaktif ({{ $users->where('is_active', false)->count() }})
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="usersTable">
                <thead class="table-light">
                    <tr>
                        <th>
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr data-role="{{ $user->role }}" data-status="{{ $user->is_active ? 'active' : 'inactive' }}">
                            <td>
                                <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-info ms-1">Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge role-{{ $user->role }}">
                                    <i class="bi bi-{{ $user->role === 'admin' ? 'shield-check' : 'person-video3' }}"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $user->is_active ? 'active' : 'inactive' }}">
                                    <i class="bi bi-{{ $user->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $user->created_at->format('d M Y') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-outline-primary btn-action" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-action" 
                                                onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-people fa-3x mb-3"></i>
                                    <p>Belum ada pengguna</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-3 border-top">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-warning"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengguna <strong id="userName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle"></i>
                    Tindakan ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" id="deleteForm" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i>
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');
    
    rows.forEach(row => {
        const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        if (name.includes(searchTerm) || email.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Filter functionality
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        const rows = document.querySelectorAll('#usersTable tbody tr');
        
        rows.forEach(row => {
            const role = row.dataset.role;
            const status = row.dataset.status;
            
            let show = false;
            
            switch(filter) {
                case 'all':
                    show = true;
                    break;
                case 'admin':
                    show = role === 'admin';
                    break;
                case 'guru':
                    show = role === 'guru';
                    break;
                case 'active':
                    show = status === 'active';
                    break;
                case 'inactive':
                    show = status === 'inactive';
                    break;
            }
            
            row.style.display = show ? '' : 'none';
        });
    });
});

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Delete user function
function deleteUser(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

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
        title: 'Error!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endpush

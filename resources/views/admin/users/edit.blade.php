@extends('layouts.app')

@section('title', 'Edit Pengguna - ' . $user->name)

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
    }

    .form-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .form-header {
        background: #f8f9fa;
        padding: 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .form-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
    }

    .input-group-text {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px 0 0 8px;
    }

    .input-group .form-control {
        border-radius: 0 8px 8px 0;
    }

    .role-card {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .role-card:hover {
        border-color: #6777ef;
        background: #f8f9ff;
    }

    .role-card.selected {
        border-color: #6777ef;
        background: #e3eaff;
    }

    .role-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #6777ef;
    }

    .role-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .role-description {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .password-strength {
        margin-top: 0.5rem;
    }

    .strength-bar {
        height: 4px;
        border-radius: 2px;
        background: #dee2e6;
        overflow: hidden;
    }

    .strength-fill {
        height: 100%;
        transition: all 0.3s;
        border-radius: 2px;
    }

    .strength-weak { background: #dc3545; width: 25%; }
    .strength-fair { background: #ffc107; width: 50%; }
    .strength-good { background: #28a745; width: 75%; }
    .strength-strong { background: #20c997; width: 100%; }

    .form-footer {
        background: #f8f9fa;
        padding: 1.5rem;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-primary {
        background: #6777ef;
        border-color: #6777ef;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
    }

    .btn-secondary {
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
    }

    .invalid-feedback {
        display: block;
        font-size: 0.875rem;
        color: #dc3545;
        margin-top: 0.25rem;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #6777ef, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 600;
        margin: 0 auto 1rem;
    }

    .user-info {
        text-align: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #f8f9ff;
        border-radius: 10px;
        border: 1px solid #e3eaff;
    }

    .password-section {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .password-section h6 {
        color: #856404;
        margin-bottom: 0.5rem;
    }

    .password-section p {
        color: #856404;
        margin: 0;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-0">
                    <i class="bi bi-pencil-square"></i>
                    Edit Pengguna
                </h1>
                <p class="mb-0 opacity-75">Ubah informasi pengguna {{ $user->name }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('admin.users') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle"></i>
                        Informasi Pengguna
                    </h5>
                </div>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" id="userForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-body">
                        <!-- User Info Display -->
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-2">{{ $user->email }}</p>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'success' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }} ms-1">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <div class="mt-2">
                                <small class="text-muted">
                                    Bergabung: {{ $user->created_at->format('d M Y') }}
                                </small>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="bi bi-person"></i>
                                Nama Lengkap
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       placeholder="Masukkan nama lengkap"
                                       required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i>
                                Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       placeholder="contoh@email.com"
                                       required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-person-badge"></i>
                                Role Pengguna
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="role-card {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}" data-role="admin">
                                        <div class="role-icon">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <div class="role-title">Administrator</div>
                                        <div class="role-description">
                                            Akses penuh mengelola pengguna dan soal
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="role-card {{ old('role', $user->role) === 'guru' ? 'selected' : '' }}" data-role="guru">
                                        <div class="role-icon">
                                            <i class="bi bi-person-workspace"></i>
                                        </div>
                                        <div class="role-title">Guru</div>
                                        <div class="role-description">
                                            Dapat membuat ujian dan melihat hasil siswa
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="role" id="role" value="{{ old('role', $user->role) }}">
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Section -->
                        <div class="password-section">
                            <h6>
                                <i class="bi bi-info-circle"></i>
                                Ubah Password
                            </h6>
                            <p>Kosongkan jika tidak ingin mengubah password. Password minimal 6 karakter.</p>
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i>
                                Password Baru (Opsional)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Kosongkan jika tidak ingin mengubah">
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength" style="display: none;">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthFill"></div>
                                </div>
                                <small class="text-muted" id="strengthText">Masukkan password</small>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="form-group" id="confirmPasswordGroup" style="display: none;">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock"></i>
                                Konfirmasi Password Baru
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Ulangi password baru">
                                <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirm">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div id="passwordMatch" class="mt-2"></div>
                        </div>

                        <!-- Status Field -->
                        <div class="form-group">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="bi bi-toggle-on"></i>
                                    Status Aktif
                                </label>
                                <small class="form-text text-muted">
                                    Nonaktifkan untuk melarang pengguna login ke sistem
                                </small>
                            </div>
                        </div>

                        @if($user->id === auth()->id())
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>Catatan:</strong> Anda sedang mengedit akun Anda sendiri. Berhati-hatilah saat mengubah role atau status.
                            </div>
                        @endif
                    </div>

                    <div class="form-footer">
                        <div>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i>
                                Terakhir diubah: {{ $user->updated_at->format('d M Y H:i') }}
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-x"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-floppy"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Role selection
document.querySelectorAll('.role-card').forEach(card => {
    card.addEventListener('click', function() {
        // Remove selected class from all cards
        document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
        
        // Add selected class to clicked card
        this.classList.add('selected');
        
        // Update hidden input
        document.getElementById('role').value = this.dataset.role;
    });
});

// Password visibility toggle
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});

document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
    const password = document.getElementById('password_confirmation');
    const icon = this.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});

// Show/hide password confirmation based on password input
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const confirmGroup = document.getElementById('confirmPasswordGroup');
    const strengthDiv = document.querySelector('.password-strength');
    
    if (password.length > 0) {
        confirmGroup.style.display = 'block';
        strengthDiv.style.display = 'block';
        
        // Password strength checker
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        
        let strength = 0;
        let text = '';
        let className = '';
        
        if (password.length < 6) {
            strength = 1;
            text = 'Terlalu pendek';
            className = 'strength-weak';
        } else {
            strength = 1;
            
            // Check for different criteria
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            switch(strength) {
                case 1:
                case 2:
                    text = 'Lemah';
                    className = 'strength-weak';
                    break;
                case 3:
                    text = 'Sedang';
                    className = 'strength-fair';
                    break;
                case 4:
                    text = 'Kuat';
                    className = 'strength-good';
                    break;
                case 5:
                    text = 'Sangat Kuat';
                    className = 'strength-strong';
                    break;
            }
        }
        
        strengthFill.className = 'strength-fill ' + className;
        strengthText.textContent = text;
    } else {
        confirmGroup.style.display = 'none';
        strengthDiv.style.display = 'none';
    }
});

// Password confirmation checker
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const matchDiv = document.getElementById('passwordMatch');
    
    if (confirmPassword.length === 0) {
        matchDiv.innerHTML = '';
    } else if (password === confirmPassword) {
        matchDiv.innerHTML = '<small class="text-success"><i class="bi bi-check"></i> Password cocok</small>';
    } else {
        matchDiv.innerHTML = '<small class="text-danger"><i class="bi bi-x"></i> Password tidak cocok</small>';
    }
});

// Form validation
document.getElementById('userForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    
    // Only validate password if it's being changed
    if (password.length > 0) {
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            return false;
        }
    }
});
</script>
@endpush

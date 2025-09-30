@extends('layouts.app')

@section('title', 'Kelola Soal')

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

    .question-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: transform 0.2s;
    }

    .question-card:hover {
        transform: translateY(-2px);
    }

    .question-header {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .question-body {
        padding: 1.5rem;
    }

    .tier-section {
        margin-bottom: 1.5rem;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #6777ef;
    }

    .tier1-section {
        background: #f8f9ff;
        border-left-color: #6777ef;
    }

    .tier2-section {
        background: #f0fff4;
        border-left-color: #47c363;
    }

    .tier-title {
        font-weight: 600;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tier1-title {
        color: #6777ef;
    }

    .tier2-title {
        color: #47c363;
    }

    .question-text {
        font-family: 'Amiri', 'Noto Sans Arabic', serif;
        font-size: 1.1rem;
        line-height: 1.6;
        direction: rtl;
        text-align: right;
        margin-bottom: 1rem;
    }

    .options-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .option-item {
        padding: 0.5rem 0;
        font-family: 'Amiri', 'Noto Sans Arabic', serif;
        direction: rtl;
        text-align: right;
    }

    .option-item.correct {
        color: #155724;
        font-weight: 600;
        background: #d4edda;
        padding: 0.5rem;
        border-radius: 4px;
    }

    .difficulty-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .difficulty-mudah {
        background: #d4edda;
        color: #155724;
    }

    .difficulty-sedang {
        background: #fff3cd;
        color: #856404;
    }

    .difficulty-sulit {
        background: #f8d7da;
        color: #721c24;
    }

    .filter-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 6px;
        margin: 0 2px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-0">
                    <i class="bi bi-question-circle"></i>
                    Kelola Soal
                </h1>
                <p class="mb-0 opacity-75">Manajemen bank soal ujian bahasa Arab</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.questions.create') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-plus"></i>
                    Tambah Soal
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="bi bi-question-circle"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $questions->total() }}</h3>
                        <p class="text-muted mb-0">Total Soal</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0">{{ $questions->where('is_active', true)->count() }}</h3>
                        <p class="text-muted mb-0">Aktif</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.questions.index') }}">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" class="form-select">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bab</label>
                    <select name="chapter_id" class="form-select">
                        <option value="">Semua Bab</option>
                        @foreach($chapters as $chapter)
                            <option value="{{ $chapter->id }}" {{ request('chapter_id') == $chapter->id ? 'selected' : '' }}>
                                {{ $chapter->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Questions List -->
    @forelse($questions as $question)
        <div class="question-card">
            <div class="question-header">
                <div class="d-flex align-items-center gap-3">
                    <h6 class="mb-0">Soal #{{ $question->id }}</h6>
                    <span class="badge bg-secondary">{{ $question->chapter->subject->name }}</span>
                    <span class="badge bg-info">{{ $question->chapter->name }}</span>
                    @if($question->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Nonaktif</span>
                    @endif
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.questions.edit', $question) }}" 
                       class="btn btn-outline-primary btn-action" 
                       title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" 
                            class="btn btn-outline-danger btn-action" 
                            onclick="deleteQuestion({{ $question->id }})"
                            title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            <div class="question-body">
                <!-- Tier 1 -->
                <div class="tier-section tier1-section">
                    <div class="tier-title tier1-title">
                        <i class="bi bi-1-circle"></i>
                        Pertanyaan Utama
                    </div>
                    <div class="question-text">{{ $question->tier1_question }}</div>
                    <ul class="options-list">
                        @if(is_array($question->tier1_options) && count($question->tier1_options) > 0)
                            @foreach($question->tier1_options as $index => $option)
                                <li class="option-item {{ $index == $question->tier1_correct_answer ? 'correct' : '' }}">
                                    @php
                                        $arabicLetters = ['أ', 'ب', 'ج', 'د', 'ه'];
                                    @endphp
                                    {{ $arabicLetters[$index] ?? chr(65 + $index) }}) {{ $option }}
                                    @if($index == $question->tier1_correct_answer)
                                        <i class="bi bi-check-circle ms-2"></i>
                                    @endif
                                </li>
                            @endforeach
                        @else
                            <li class="option-item text-danger">
                                <i class="bi bi-exclamation-triangle"></i>
                                Error: Format opsi tidak valid
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Tier 2 -->
                <div class="tier-section tier2-section">
                    <div class="tier-title tier2-title">
                        <i class="bi bi-2-circle"></i>
                        Alasan Pemilihan Jawaban
                    </div>
                    <div class="question-text">{{ $question->tier2_question }}</div>
                    <ul class="options-list">
                        @if(is_array($question->tier2_options) && count($question->tier2_options) > 0)
                            @foreach($question->tier2_options as $index => $option)
                                <li class="option-item {{ $index == $question->tier2_correct_answer ? 'correct' : '' }}">
                                    @php
                                        $arabicLetters = ['أ', 'ب', 'ج', 'د', 'ه'];
                                    @endphp
                                    {{ $arabicLetters[$index] ?? ($index + 1) }}) {{ $option }}
                                    @if($index == $question->tier2_correct_answer)
                                        <i class="bi bi-check-circle ms-2"></i>
                                    @endif
                                </li>
                            @endforeach
                        @else
                            <li class="option-item text-danger">
                                <i class="bi bi-exclamation-triangle"></i>
                                Error: Format opsi tidak valid
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                    <small class="text-muted">
                        <i class="bi bi-person"></i>
                        Dibuat oleh: {{ $question->creator->name }} • 
                        <i class="bi bi-calendar"></i>
                        {{ $question->created_at->format('d M Y') }}
                    </small>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-question-circle display-1 text-muted"></i>
            <h4 class="mt-3">Belum ada soal</h4>
            <p class="text-muted">Mulai dengan menambahkan soal pertama</p>
            <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i>
                Tambah Soal
            </a>
        </div>
    @endforelse

    @if($questions->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $questions->links() }}
        </div>
    @endif
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
                <p>Apakah Anda yakin ingin menghapus soal ini?</p>
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
function deleteQuestion(questionId) {
    document.getElementById('deleteForm').action = `/admin/questions/${questionId}`;
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

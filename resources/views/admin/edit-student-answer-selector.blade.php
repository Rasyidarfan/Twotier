@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Edit Jawaban Siswa</h2>
                    <p class="text-muted mb-0">Pilih session yang ingin diedit</p>
                </div>
                <div>
                    <form action="{{ route('admin.recalculate-all-scores') }}" method="POST" id="recalculateForm" class="d-inline">
                        @csrf
                        <button type="button" class="btn btn-warning" onclick="confirmRecalculate()">
                            <i class="bi bi-calculator"></i> Hitung Ulang Semua Skor
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Pilih Session</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="searchSession" class="form-control" placeholder="Cari nama siswa atau exam...">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="sessionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Session ID</th>
                                    <th>Nama Siswa</th>
                                    <th>Ujian</th>
                                    <th>Total Skor</th>
                                    <th>Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSessions as $session)
                                <tr>
                                    <td>{{ $session->id }}</td>
                                    <td>{{ $session->student_name }}</td>
                                    <td>{{ $session->exam->title }}</td>
                                    <td>{{ $session->total_score }}</td>
                                    <td>{{ $session->finished_at ? $session->finished_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.edit-student-answer', $session->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('searchSession').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#sessionsTable tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

function confirmRecalculate() {
    if (confirm('PERINGATAN!\n\nFitur ini akan menghitung ulang SEMUA skor siswa di SEMUA ujian berdasarkan jawaban yang tersimpan (tier1_answer dan tier2_answer).\n\nProses ini akan:\n✓ Menghitung ulang result_category\n✓ Menghitung ulang points_earned\n✓ Menghitung ulang total_score per session\n✓ Update scoring_breakdown\n✓ Clear cache analisis butir soal\n\nApakah Anda yakin ingin melanjutkan?')) {
        const form = document.getElementById('recalculateForm');
        const submitBtn = form.querySelector('button');

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghitung ulang...';

        form.submit();
    }
}
</script>
@endpush
@endsection

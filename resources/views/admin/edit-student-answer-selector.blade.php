@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-1">Edit Jawaban Siswa</h2>
            <p class="text-muted">Pilih session yang ingin diedit</p>
        </div>
    </div>

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
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Guru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-calendar"></i>
                {{ date('d M Y') }}
            </button>
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
                            Total Ujian
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['total_exams'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-clipboard-check fs-2 text-primary"></i>
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
                            Ujian Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['active_exams'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-play-circle fs-2 text-success"></i>
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
                            Total Peserta
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['total_participants'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people fs-2 text-info"></i>
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
                            Bulan Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ date('M Y') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-month fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('guru.exams.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle"></i>
                            Buat Ujian Baru
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('guru.questions') }}" class="btn btn-success w-100">
                            <i class="bi bi-collection"></i>
                            Bank Soal
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('guru.exams') }}" class="btn btn-info w-100">
                            <i class="bi bi-clipboard-check"></i>
                            Kelola Ujian
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('exam.join') }}" class="btn btn-warning w-100" target="_blank">
                            <i class="bi bi-box-arrow-up-right"></i>
                            Portal Siswa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Exams -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history"></i>
                    Ujian Terbaru
                </h5>
                <a href="{{ route('guru.exams') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($stats['recent_exams']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Judul Ujian</th>
                                    <th>Kode</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Durasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_exams'] as $exam)
                                    <tr>
                                        <td>
                                            <strong>{{ $exam->title }}</strong>
                                            @if($exam->description)
                                                <br><small class="text-muted">{{ Str::limit($exam->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">{{ $exam->code }}</span>
                                        </td>
                                        <td>{{ $exam->subject->name }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $exam->grade }} - {{ ucfirst($exam->semester) }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($exam->status)
                                                @case('draft')
                                                    <span class="badge bg-secondary">Draft</span>
                                                    @break
                                                @case('waiting')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                    @break
                                                @case('active')
                                                    <span class="badge bg-success">Aktif</span>
                                                    @break
                                                @case('finished')
                                                    <span class="badge bg-danger">Selesai</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $exam->duration_minutes }} menit</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if($exam->status === 'draft')
                                                    <a href="{{ route('guru.exams.waiting-room', $exam) }}" class="btn btn-outline-primary">
                                                        <i class="bi bi-play"></i>
                                                    </a>
                                                @elseif($exam->status === 'waiting')
                                                    <a href="{{ route('guru.exams.waiting-room', $exam) }}" class="btn btn-outline-success">
                                                        <i class="bi bi-people"></i>
                                                    </a>
                                                @elseif($exam->status === 'active')
                                                    <a href="{{ route('guru.exams.waiting-room', $exam) }}" class="btn btn-outline-warning">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('guru.exams.results', $exam) }}" class="btn btn-outline-info">
                                                        <i class="bi bi-bar-chart"></i>
                                                    </a>
                                                @endif
                                                
                                                <a href="{{ route('guru.exams.edit', $exam) }}" class="btn btn-outline-secondary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Belum ada ujian yang dibuat</p>
                        <a href="{{ route('guru.exams.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i>
                            Buat Ujian Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .text-xs {
        font-size: 0.7rem;
    }
    
    .text-gray-800 {
        color: #5a5c69 !important;
    }
</style>
@endsection

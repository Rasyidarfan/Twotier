@extends('layouts.app')

@section('title', 'Ruang Monitor - ' . $exam->title)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">{{ $exam->title }}</h4>
                            <p class="text-muted mb-0">
                                {{ $exam->subject->name }} • {{ $exam->grade }} • {{ ucfirst($exam->semester) }}
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="d-flex align-items-center gap-2 justify-content-end">
                                <div>
                                    <h2 class="mb-0 fw-bold text-primary" id="current-code">{{ $exam->getCurrentCode() }}</h2>
                                    <small class="text-muted">Kode Ujian Aktif</small>
                                </div>
                                @if($exam->status === 'waiting')
                                <button class="btn btn-outline-primary btn-sm" onclick="regenerateCode()" title="Generate Ulang Kode">
                                    <i class="bi bi-arrow-clockwise"></i> Generate Ulang
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-0" id="stat-total">{{ $stats['total'] }}</h3>
                    <small>Total Peserta</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h3 class="mb-0" id="stat-waiting">{{ $stats['waiting_identity'] + $stats['waiting_approval'] }}</h3>
                    <small>Menunggu</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h3 class="mb-0" id="stat-approved">{{ $stats['approved'] }}</h3>
                    <small>Disetujui</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h3 class="mb-0" id="stat-active">{{ $stats['in_progress'] }}</h3>
                    <small>Mengerjakan</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-secondary text-white">
                <div class="card-body">
                    <h3 class="mb-0" id="stat-finished">{{ $stats['finished'] }}</h3>
                    <small>Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <h3 class="mb-0" id="stat-kicked">{{ $stats['kicked'] }}</h3>
                    <small>Dikeluarkan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Control Panel -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <!-- Status Controls -->
                        <div class="btn-group" role="group">
                            @if($exam->status === 'waiting')
                                <button class="btn btn-success" onclick="updateExamStatus('active')">
                                    <i class="bi bi-play-fill"></i> Mulai Ujian
                                </button>
                            @endif
                            @if($exam->status === 'active')
                                <button class="btn btn-danger" onclick="updateExamStatus('finished')">
                                    <i class="bi bi-stop-fill"></i> Akhiri Ujian
                                </button>
                            @endif
                            <button class="btn btn-warning" onclick="updateExamStatus('waiting')">
                                <i class="bi bi-pause-fill"></i> Set Waiting
                            </button>
                        </div>

                        <div class="vr"></div>

                        <!-- Time Extension -->
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select form-select-sm" id="extend-minutes" style="width: auto;">
                                <option value="5">5 menit</option>
                                <option value="10">10 menit</option>
                                <option value="15">15 menit</option>
                                <option value="30">30 menit</option>
                            </select>
                            <button class="btn btn-outline-primary btn-sm" onclick="extendTimeAll()">
                                <i class="bi bi-clock"></i> Tambah Waktu Semua
                            </button>
                        </div>

                        <div class="vr"></div>

                        <!-- Export -->
                        <button class="btn btn-outline-success btn-sm" onclick="exportCurrentResults()">
                            <i class="bi bi-download"></i> Export Hasil Sementara
                        </button>

                        <div class="vr"></div>

                        <!-- Message Broadcast -->
                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#broadcastModal">
                            <i class="bi bi-megaphone"></i> Kirim Pesan
                        </button>

                        <div class="ms-auto">
                            <span class="badge bg-{{ $exam->status === 'active' ? 'success' : ($exam->status === 'waiting' ? 'warning' : 'secondary') }} fs-6">
                                {{ strtoupper($exam->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Participants Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Peserta</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" id="filter-status" onchange="filterParticipants()">
                            <option value="">Semua Status</option>
                            <option value="waiting_identity">Menunggu Identitas</option>
                            <option value="waiting_approval">Menunggu Persetujuan</option>
                            <option value="approved">Disetujui</option>
                            <option value="in_progress">Mengerjakan</option>
                            <option value="finished">Selesai</option>
                            <option value="timeout">Timeout</option>
                            <option value="kicked">Dikeluarkan</option>
                        </select>
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshParticipants()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="participants-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Identitas</th>
                                    <th>Status</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Mulai</th>
                                    <th>Progress</th>
                                    <th>Skor</th>
                                    <th>Zona Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="participants-tbody">
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Broadcast Message Modal -->
<div class="modal fade" id="broadcastModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kirim Pesan ke Semua Peserta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="broadcast-form">
                    <div class="mb-3">
                        <label class="form-label">Jenis Pesan</label>
                        <select class="form-select" name="type" required>
                            <option value="info">Info</option>
                            <option value="warning">Peringatan</option>
                            <option value="success">Sukses</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Pesan</label>
                        <textarea class="form-control" name="content" rows="4" maxlength="500" placeholder="Masukkan pesan..." required></textarea>
                        <div class="form-text">Maksimal 500 karakter</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="sendBroadcast()">Kirim Pesan</button>
            </div>
        </div>
    </div>
</div>

<!-- Extend Time Modal -->
<div class="modal fade" id="extendTimeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Perpanjang Waktu Peserta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="extend-time-form">
                    <input type="hidden" id="extend-participant-id" name="participant_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Peserta</label>
                        <input type="text" class="form-control" id="extend-participant-name" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tambah Waktu (menit)</label>
                        <select class="form-select" name="minutes" required>
                            <option value="5">5 menit</option>
                            <option value="10">10 menit</option>
                            <option value="15">15 menit</option>
                            <option value="30">30 menit</option>
                            <option value="60">60 menit</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" onclick="extendTimeParticipant()">Perpanjang Waktu</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const examId = {{ $exam->id }};
let participantsData = [];
let refreshInterval;

$(document).ready(function() {
    refreshParticipants();
    startAutoRefresh();
});

function startAutoRefresh() {
    refreshInterval = setInterval(refreshParticipants, 5000); // Refresh every 5 seconds
}

function regenerateCode() {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin generate ulang kode ujian?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Generate Ulang',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(`/guru/exams/${examId}/regenerate-code`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function(response) {
                if (response.success) {
                    $('#current-code').text(response.code);
                    Swal.fire({
                        title: 'Berhasil!',
                        html: `Kode baru: <strong class="text-primary fs-4">${response.code}</strong>`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            }).fail(function(xhr) {
                const response = xhr.responseJSON;
                Swal.fire({
                    title: 'Gagal!',
                    text: response.message || 'Terjadi kesalahan',
                    icon: 'error'
                });
            });
        }
    });
}

function refreshParticipants() {
    $.get(`/guru/exams/${examId}/participants`, function(data) {
        participantsData = data.participants;
        updateStats(data.statistics);
        renderParticipants();
        updateCurrentCode(data.current_code);
    }).fail(function() {
        console.error('Failed to refresh participants');
    });
}

function updateStats(stats) {
    $('#stat-total').text(stats.total);
    $('#stat-waiting').text(stats.waiting_identity + stats.waiting_approval);
    $('#stat-approved').text(stats.approved);
    $('#stat-active').text(stats.in_progress);
    $('#stat-finished').text(stats.finished);
    $('#stat-kicked').text(stats.kicked);
}

function updateCurrentCode(code) {
    if (code) {
        $('#current-code').text(code);
    }
}

function renderParticipants() {
    const tbody = $('#participants-tbody');
    const filterStatus = $('#filter-status').val();
    
    let filteredData = participantsData;
    if (filterStatus) {
        filteredData = participantsData.filter(p => p.status === filterStatus);
    }
    
    tbody.empty();
    
    if (filteredData.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="10" class="text-center text-muted py-4">
                    <i class="bi bi-people fs-1 mb-2"></i><br>
                    Belum ada peserta
                </td>
            </tr>
        `);
        return;
    }
    
    filteredData.forEach((participant, index) => {
        const statusBadge = getStatusBadge(participant.status);
        const progressBar = getProgressBar(participant.progress);
        const actionButtons = getActionButtons(participant);
        
        tbody.append(`
            <tr>
                <td>${index + 1}</td>
                <td>
                    <strong>${participant.name}</strong>
                    ${participant.extended_time > 0 ? `<br><small class="text-warning"><i class="bi bi-clock"></i> +${participant.extended_time}m</small>` : ''}
                </td>
                <td>${participant.identifier}</td>
                <td>${statusBadge}</td>
                <td>${participant.joined_at}</td>
                <td>${participant.started_at}</td>
                <td>${progressBar}</td>
                <td><span class="badge bg-primary">${participant.current_score}</span></td>
                <td><small class="text-muted">${participant.timezone}</small></td>
                <td>${actionButtons}</td>
            </tr>
        `);
    });
}

function getStatusBadge(status) {
    const badges = {
        'waiting_identity': '<span class="badge bg-warning">Menunggu Identitas</span>',
        'waiting_approval': '<span class="badge bg-info">Menunggu Persetujuan</span>',
        'approved': '<span class="badge bg-success">Disetujui</span>',
        'in_progress': '<span class="badge bg-primary">Mengerjakan</span>',
        'finished': '<span class="badge bg-secondary">Selesai</span>',
        'timeout': '<span class="badge bg-warning">Timeout</span>',
        'kicked': '<span class="badge bg-danger">Dikeluarkan</span>'
    };
    return badges[status] || `<span class="badge bg-light">${status}</span>`;
}

function getProgressBar(progress) {
    if (progress === 0) {
        return '<span class="text-muted">-</span>';
    }
    
    const colorClass = progress < 50 ? 'bg-warning' : progress < 100 ? 'bg-info' : 'bg-success';
    return `
        <div class="progress" style="height: 6px;">
            <div class="progress-bar ${colorClass}" style="width: ${progress}%"></div>
        </div>
        <small class="text-muted">${progress}%</small>
    `;
}

function getActionButtons(participant) {
    let buttons = [];
    
    // Approval button for waiting participants
    if (participant.status === 'waiting_approval') {
        buttons.push(`<button class="btn btn-sm btn-success" onclick="approveParticipant(${participant.id})" title="Setujui">
            <i class="bi bi-check-lg"></i>
        </button>`);
    }
    
    // Extend time button for active participants
    if (['approved', 'in_progress'].includes(participant.status)) {
        buttons.push(`<button class="btn btn-sm btn-warning" onclick="openExtendTimeModal(${participant.id}, '${participant.name}')" title="Perpanjang Waktu">
            <i class="bi bi-clock"></i>
        </button>`);
    }
    
    // Kick button for non-finished participants
    if (!['finished', 'timeout', 'kicked'].includes(participant.status)) {
        buttons.push(`<button class="btn btn-sm btn-danger" onclick="kickParticipant(${participant.id}, '${participant.name}')" title="Keluarkan">
            <i class="bi bi-x-circle"></i>
        </button>`);
    }
    
    return buttons.join(' ') || '<span class="text-muted">-</span>';
}

function filterParticipants() {
    renderParticipants();
}

// Action functions
function updateExamStatus(status) {
    const messages = {
        'waiting': 'mengatur ujian ke status waiting',
        'active': 'memulai ujian',
        'finished': 'mengakhiri ujian'
    };
    
    const icons = {
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
            $.post(`/guru/exams/${examId}/status`, {
                status: status,
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            }).fail(function(xhr) {
                const response = xhr.responseJSON;
                Swal.fire({
                    title: 'Gagal!',
                    text: response.message || 'Terjadi kesalahan',
                    icon: 'error'
                });
            });
        }
    });
}

function approveParticipant(participantId) {
    $.post(`/guru/exams/${examId}/participants/${participantId}/approve`, {
        _token: $('meta[name="csrf-token"]').attr('content')
    }).done(function(response) {
        if (response.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: response.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                refreshParticipants();
            });
        }
    }).fail(function(xhr) {
        const response = xhr.responseJSON;
        Swal.fire({
            title: 'Gagal!',
            text: response.message || 'Terjadi kesalahan',
            icon: 'error'
        });
    });
}

function kickParticipant(participantId, participantName) {
    Swal.fire({
        title: 'Konfirmasi',
        text: `Apakah Anda yakin ingin mengeluarkan ${participantName} dari ujian?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Keluarkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(`/guru/exams/${examId}/participants/${participantId}/kick`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        refreshParticipants();
                    });
                }
            }).fail(function(xhr) {
                const response = xhr.responseJSON;
                Swal.fire({
                    title: 'Gagal!',
                    text: response.message || 'Terjadi kesalahan',
                    icon: 'error'
                });
            });
        }
    });
}

function extendTime(minutes, participantId = null) {
    const url = participantId 
        ? `/guru/exams/${examId}/participants/${participantId}/extend-time`
        : `/guru/exams/${examId}/extend-time`;
        
    $.post(url, {
        minutes: minutes,
        _token: $('meta[name="csrf-token"]').attr('content')
    }).done(function(response) {
        if (response.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: response.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                refreshParticipants();
            });
        }
    }).fail(function(xhr) {
        const response = xhr.responseJSON;
        Swal.fire({
            title: 'Gagal!',
            text: response.message || 'Terjadi kesalahan',
            icon: 'error'
        });
    });
}

function openExtendTimeModal(participantId, participantName) {
    $('#extend-participant-id').val(participantId);
    $('#extend-participant-name').val(participantName);
    $('#extendTimeModal').modal('show');
}

function extendTimeParticipant() {
    const form = $('#extend-time-form');
    const participantId = $('#extend-participant-id').val();
    const minutes = form.find('select[name="minutes"]').val();
    
    $.post(`/guru/exams/${examId}/participants/${participantId}/extend-time`, {
        minutes: minutes,
        _token: $('meta[name="csrf-token"]').attr('content')
    }).done(function(response) {
        if (response.success) {
            toastr.success(response.message);
            $('#extendTimeModal').modal('hide');
            refreshParticipants();
        }
    }).fail(function(xhr) {
        const response = xhr.responseJSON;
        toastr.error(response.message || 'Terjadi kesalahan');
    });
}

function broadcastMessage() {
    Swal.fire({
        title: 'Kirim Pesan Broadcast',
        html: `
            <div class="mb-3">
                <label class="form-label">Tipe Pesan</label>
                <select id="messageType" class="form-select">
                    <option value="info">Info</option>
                    <option value="success">Sukses</option>
                    <option value="warning">Peringatan</option>
                    <option value="error">Error</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Pesan</label>
                <textarea id="messageContent" class="form-control" rows="3"></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Kirim',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const type = $('#messageType').val();
            const content = $('#messageContent').val();
            
            if (!content) {
                Swal.showValidationMessage('Isi pesan tidak boleh kosong');
                return false;
            }
            
            return { type, content };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { type, content } = result.value;
            
            $.post(`/guru/exams/${examId}/broadcast-message`, {
                type: type,
                content: content,
                _token: $('meta[name="csrf-token"]').attr('content')
            }).done(function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            }).fail(function(xhr) {
                const response = xhr.responseJSON;
                Swal.fire({
                    title: 'Gagal!',
                    text: response.message || 'Terjadi kesalahan',
                    icon: 'error'
                });
            });
        }
    });
}

function exportCurrentResults() {
    window.location.href = `/guru/exams/${examId}/export-current-results`;
}

// Cleanup intervals when leaving the page
$(window).on('beforeunload', function() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>
@endpush
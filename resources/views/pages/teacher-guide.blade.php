@extends('layouts.public')

@section('title', 'Panduan Untuk Guru - Twotier Exam System')
@section('description', 'Panduan lengkap cara menggunakan sistem ujian two-tier untuk guru. Pelajari cara membuat ujian, mengelola peserta, dan melihat hasil analisis.')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1><i class="bi bi-mortarboard-fill me-3"></i>Panduan Untuk Guru</h1>
                <p class="lead mb-0">Panduan lengkap mengelola ujian diagnostik two-tier</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Pendahuluan -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <h2 class="text-gradient mb-4">
                        <i class="bi bi-info-circle me-2"></i>Tentang Dashboard Guru
                    </h2>
                    <p class="lead">Dashboard guru adalah pusat kontrol untuk mengelola ujian two-tier, dari pembuatan soal hingga analisis hasil.</p>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <div class="bg-light rounded-circle p-3 d-inline-block mb-3">
                                    <i class="bi bi-file-earmark-text-fill text-primary fs-2"></i>
                                </div>
                                <h5>Kelola Soal</h5>
                                <p class="text-muted small mb-0">Buat dan kelola bank soal two-tier</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <div class="bg-light rounded-circle p-3 d-inline-block mb-3">
                                    <i class="bi bi-clipboard-check-fill text-primary fs-2"></i>
                                </div>
                                <h5>Buat Ujian</h5>
                                <p class="text-muted small mb-0">Atur ujian dengan mudah dan cepat</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <div class="bg-light rounded-circle p-3 d-inline-block mb-3">
                                    <i class="bi bi-graph-up-arrow text-primary fs-2"></i>
                                </div>
                                <h5>Analisis Hasil</h5>
                                <p class="text-muted small mb-0">Lihat analisis butir soal dan hasil siswa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Langkah-langkah -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <h2 class="text-gradient mb-4">
                <i class="bi bi-list-ol me-2"></i>Langkah-Langkah Mengelola Ujian
            </h2>

            <!-- Langkah 1: Login -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-1-circle-fill me-2"></i>Login ke Dashboard</h4>
                </div>
                <div class="card-body">
                    <p>Akses dashboard guru melalui halaman login:</p>
                    <ol>
                        <li>Buka halaman: <code>{{ route('login') }}</code></li>
                        <li>Masukkan email dan password yang terdaftar</li>
                        <li>Klik tombol "Login"</li>
                    </ol>
                    <div class="p-3 bg-light rounded mb-3">
                        <a href="{{ route('login') }}" class="btn btn-gradient-primary">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login ke Dashboard
                        </a>
                    </div>
                    <img src="{{ asset('images/guides/teacher/placeholder-1.jpg') }}"
                         alt="Halaman login"
                         class="img-fluid rounded shadow-sm mt-3">
                </div>
            </div>

            <!-- Langkah 2: Dashboard Overview -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-2-circle-fill me-2"></i>Navigasi Dashboard</h4>
                </div>
                <div class="card-body">
                    <p>Setelah login, Anda akan melihat dashboard dengan menu:</p>
                    <ul>
                        <li><strong>Dashboard:</strong> Ringkasan statistik ujian</li>
                        <li><strong>Ujian:</strong> Daftar ujian dan pengelolaan</li>
                        <li><strong>Bank Soal:</strong> Kelola soal two-tier</li>
                        <li><strong>Analisis:</strong> Analisis butir soal dan hasil</li>
                    </ul>
                </div>
            </div>

            <!-- Langkah 3: Buat Ujian -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-3-circle-fill me-2"></i>Membuat Ujian Baru</h4>
                </div>
                <div class="card-body">
                    <p>Untuk membuat ujian baru:</p>
                    <ol>
                        <li>Klik menu "Ujian" di sidebar</li>
                        <li>Klik tombol "Buat Ujian Baru"</li>
                        <li>Isi form dengan informasi:</li>
                    </ol>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Field</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Judul Ujian</strong></td>
                                    <td>Nama ujian (contoh: "Ujian Tengah Semester Bahasa Arab")</td>
                                </tr>
                                <tr>
                                    <td><strong>Deskripsi</strong></td>
                                    <td>Penjelasan singkat tentang ujian (opsional)</td>
                                </tr>
                                <tr>
                                    <td><strong>Mata Pelajaran</strong></td>
                                    <td>Pilih mata pelajaran dari dropdown</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>Pilih kelas target (10, 11, 12)</td>
                                </tr>
                                <tr>
                                    <td><strong>Semester</strong></td>
                                    <td>Gasal atau Genap</td>
                                </tr>
                                <tr>
                                    <td><strong>Durasi</strong></td>
                                    <td>Lama waktu ujian dalam menit</td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu Mulai</strong></td>
                                    <td>Kapan ujian dapat diakses (opsional)</td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu Selesai</strong></td>
                                    <td>Batas akhir mengikuti ujian (opsional)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <img src="{{ asset('images/guides/teacher/placeholder-2.jpg') }}"
                         alt="Form buat ujian"
                         class="img-fluid rounded shadow-sm mt-3">
                </div>
            </div>

            <!-- Langkah 4: Tambah Soal -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-4-circle-fill me-2"></i>Menambah Soal ke Ujian</h4>
                </div>
                <div class="card-body">
                    <p>Setelah membuat ujian, tambahkan soal:</p>
                    <h5 class="mt-4 mb-3">Dari Bank Soal</h5>
                    <ol>
                        <li>Klik tombol "Tambah Soal" pada detail ujian</li>
                        <li>Pilih soal dari bank soal yang tersedia</li>
                        <li>Soal akan otomatis ditambahkan ke ujian</li>
                        <li>Atur urutan soal jika diperlukan</li>
                    </ol>
                    <h5 class="mt-4 mb-3">Membuat Soal Two-Tier</h5>
                    <p>Setiap soal two-tier terdiri dari:</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="bi bi-1-circle me-2"></i>Tier 1</h6>
                                    <ul class="small mb-0">
                                        <li>Pertanyaan utama</li>
                                        <li>5 pilihan jawaban (A-E)</li>
                                        <li>Tandai jawaban yang benar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="bi bi-2-circle me-2"></i>Tier 2</h6>
                                    <ul class="small mb-0">
                                        <li>Pertanyaan lanjutan (alasan)</li>
                                        <li>5 pilihan jawaban (A-E)</li>
                                        <li>Tandai jawaban yang benar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Langkah 5: Generate Kode -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-5-circle-fill me-2"></i>Generate Kode Ujian</h4>
                </div>
                <div class="card-body">
                    <p>Setelah soal ditambahkan, generate kode ujian:</p>
                    <ol>
                        <li>Pada halaman detail ujian, klik "Mulai Ujian"</li>
                        <li>Sistem akan generate kode unik 6 karakter</li>
                        <li>Status ujian berubah menjadi "Active"</li>
                    </ol>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Kode ujian akan ditampilkan dengan format besar dan jelas untuk dibagikan ke siswa.
                    </div>
                </div>
            </div>

            <!-- Langkah 6: Bagikan Kode -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-6-circle-fill me-2"></i>Membagikan Kode ke Siswa</h4>
                </div>
                <div class="card-body">
                    <p>Bagikan kode ujian ke siswa dengan cara:</p>
                    <ul>
                        <li>Tampilkan kode di layar proyektor</li>
                        <li>Tulis kode di papan tulis</li>
                        <li>Kirim melalui grup WhatsApp kelas</li>
                        <li>Berikan secara lisan</li>
                    </ul>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Pastikan siswa mengakses halaman: <code>{{ route('exam.join') }}</code>
                    </div>
                </div>
            </div>

            <!-- Langkah 7: Waiting Room -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-7-circle-fill me-2"></i>Mengelola Waiting Room</h4>
                </div>
                <div class="card-body">
                    <p>Siswa yang join akan masuk waiting room terlebih dahulu:</p>
                    <ol>
                        <li>Lihat daftar siswa di waiting room</li>
                        <li>Verifikasi nama dan identitas siswa</li>
                        <li>Klik "Setujui" untuk siswa yang valid</li>
                        <li>Klik "Tolak" jika ada nama yang tidak sesuai</li>
                    </ol>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Tips:</strong> Setujui siswa secara batch dengan centang semua dan klik "Setujui Semua".
                    </div>
                </div>
            </div>

            <!-- Langkah 8: Mulai Ujian -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-8-circle-fill me-2"></i>Memulai Sesi Ujian</h4>
                </div>
                <div class="card-body">
                    <p>Setelah siswa disetujui:</p>
                    <ul>
                        <li>Siswa akan otomatis masuk ke halaman mulai ujian</li>
                        <li>Instruksikan siswa untuk membaca petunjuk</li>
                        <li>Siswa klik "Mulai Ujian" untuk memulai</li>
                        <li>Timer akan mulai berjalan secara otomatis</li>
                    </ul>
                </div>
            </div>

            <!-- Langkah 9: Monitor -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-9-circle-fill me-2"></i>Memantau Progress Siswa</h4>
                </div>
                <div class="card-body">
                    <p>Pantau progress siswa secara real-time:</p>
                    <ul>
                        <li>Lihat jumlah siswa yang sedang mengerjakan</li>
                        <li>Lihat jumlah siswa yang sudah selesai</li>
                        <li>Monitor progress per siswa (opsional)</li>
                    </ul>
                    <div class="alert alert-info">
                        <i class="bi bi-eye-fill me-2"></i>
                        Dashboard akan otomatis update tanpa perlu refresh halaman.
                    </div>
                </div>
            </div>

            <!-- Langkah 10: Akhiri Ujian -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-check2-square me-2"></i>Mengakhiri Ujian</h4>
                </div>
                <div class="card-body">
                    <p>Untuk mengakhiri ujian:</p>
                    <ol>
                        <li>Tunggu hingga waktu habis (otomatis) atau</li>
                        <li>Klik tombol "Akhiri Ujian" secara manual</li>
                        <li>Konfirmasi pengakhiran ujian</li>
                        <li>Semua siswa akan otomatis diselesaikan</li>
                    </ol>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Pastikan semua siswa sudah selesai sebelum mengakhiri ujian secara manual.
                    </div>
                </div>
            </div>

            <!-- Langkah 11: Lihat Hasil -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-trophy-fill me-2"></i>Melihat Hasil dan Statistik</h4>
                </div>
                <div class="card-body">
                    <p>Setelah ujian selesai, akses hasil:</p>
                    <h5 class="mt-4 mb-3">Hasil Per Siswa</h5>
                    <ul>
                        <li>Nama siswa dan identitas</li>
                        <li>Total skor yang diperoleh</li>
                        <li>Breakdown: BB, BS, SB, SS</li>
                        <li>Waktu pengerjaan</li>
                    </ul>
                    <h5 class="mt-4 mb-3">Statistik Ujian</h5>
                    <ul>
                        <li>Nilai rata-rata kelas</li>
                        <li>Nilai tertinggi dan terendah</li>
                        <li>Distribusi nilai</li>
                        <li>Persentase ketuntasan</li>
                    </ul>
                </div>
            </div>

            <!-- Langkah 12: Export -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-download me-2"></i>Export Hasil</h4>
                </div>
                <div class="card-body">
                    <p>Export hasil ujian dalam berbagai format:</p>
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="bi bi-file-earmark-excel me-2"></i>Export ke Excel</h6>
                            <p class="small">Untuk analisis lebih lanjut dan pengolahan data</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="bi bi-file-earmark-pdf me-2"></i>Export ke PDF</h6>
                            <p class="small">Untuk dokumentasi dan laporan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fitur Lanjutan -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <h2 class="text-gradient mb-4">
                <i class="bi bi-star-fill me-2"></i>Fitur Lanjutan
            </h2>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h4 class="mb-0"><i class="bi bi-graph-up me-2"></i>Analisis Butir Soal</h4>
                </div>
                <div class="card-body">
                    <p>Fitur analisis butir soal membantu Anda mengevaluasi kualitas soal:</p>
                    <ul>
                        <li><strong>Tingkat Kesukaran:</strong> Mudah, sedang, atau sulit</li>
                        <li><strong>Daya Pembeda:</strong> Kemampuan soal membedakan siswa pandai dan kurang</li>
                        <li><strong>Efektivitas Distractor:</strong> Kualitas pilihan jawaban salah</li>
                        <li><strong>Reliabilitas:</strong> Konsistensi soal dalam mengukur kemampuan</li>
                    </ul>
                    <p class="mb-0">Gunakan hasil analisis untuk memperbaiki soal di ujian berikutnya.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <h2 class="text-gradient mb-4">
                <i class="bi bi-question-circle-fill me-2"></i>Pertanyaan Umum (FAQ)
            </h2>

            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            Berapa lama kode ujian berlaku?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Kode ujian berlaku selama ujian dalam status "Active". Setelah ujian diakhiri atau waktu habis, kode tidak dapat digunakan lagi.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Apakah bisa menggunakan soal yang sama untuk ujian berbeda?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Ya, soal dari bank soal dapat digunakan berulang kali untuk ujian yang berbeda. Ini memudahkan Anda membuat ujian paralel atau remedial.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Bagaimana jika ada siswa yang terlambat join?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Siswa yang terlambat join masih bisa mengikuti ujian selama ujian masih aktif. Namun, mereka hanya memiliki sisa waktu yang tersedia. Timer tidak reset untuk siswa yang terlambat.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Apakah hasil ujian langsung tersedia?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Ya, hasil ujian tersedia langsung setelah siswa menyelesaikan ujian. Anda dapat melihat hasil individu dan statistik kelas secara real-time.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            Bagaimana cara menghapus ujian yang sudah dibuat?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Ujian yang sudah memiliki peserta tidak dapat dihapus untuk menjaga integritas data. Anda dapat mengubah status menjadi "Tidak Aktif" atau mengarsipkan ujian tersebut.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card bg-gradient-primary text-white text-center">
                <div class="card-body p-5">
                    <h3 class="mb-3">Siap Membuat Ujian?</h3>
                    <p class="lead mb-4">Login ke dashboard untuk mulai membuat dan mengelola ujian two-tier.</p>
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .accordion-button:not(.collapsed) {
        background-color: #f3f4f6;
        color: #667eea;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: #667eea;
    }
</style>
@endpush

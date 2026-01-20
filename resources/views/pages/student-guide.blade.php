@extends('layouts.public')

@section('title', 'Panduan Untuk Siswa - Twotier Exam System')
@section('description', 'Panduan lengkap cara menggunakan sistem ujian two-tier untuk siswa. Pelajari langkah demi langkah dari memasukkan kode ujian hingga melihat hasil.')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1><i class="bi bi-book-fill me-3"></i>Panduan Untuk Siswa</h1>
                <p class="lead mb-0">Pelajari cara mengikuti ujian dengan sistem two-tier step by step</p>
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
                        <i class="bi bi-info-circle me-2"></i>Tentang Sistem Ujian Two-Tier
                    </h2>
                    <p class="lead">Sistem ujian two-tier adalah metode ujian diagnostik yang terdiri dari dua tingkat pertanyaan untuk setiap soal.</p>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-light rounded-circle p-3 me-3">
                                    <i class="bi bi-1-circle-fill text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h5>Tier 1 (Tingkat 1)</h5>
                                    <p class="text-muted mb-0">Pertanyaan utama tentang materi yang diujikan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="bg-light rounded-circle p-3 me-3">
                                    <i class="bi bi-2-circle-fill text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h5>Tier 2 (Tingkat 2)</h5>
                                    <p class="text-muted mb-0">Pertanyaan lanjutan untuk mengetahui alasan jawaban Anda di Tier 1.</p>
                                </div>
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
                <i class="bi bi-list-ol me-2"></i>Langkah-Langkah Mengikuti Ujian
            </h2>

            <!-- Langkah 1 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-1-circle-fill me-2"></i>Mendapatkan Kode Ujian</h4>
                </div>
                <div class="card-body">
                    <p>Guru akan memberikan kode ujian 6 digit kepada Anda. Kode ini unik untuk setiap ujian dan hanya berlaku untuk satu sesi ujian.</p>
                    <div class="alert alert-info">
                        <i class="bi bi-lightbulb-fill me-2"></i>
                        <strong>Tips:</strong> Catat kode ujian dengan benar. Kode bersifat case-insensitive (huruf besar/kecil sama saja).
                    </div>
                    <img src="{{ asset('images/guides/student/placeholder-1.jpg') }}"
                         alt="Contoh kode ujian"
                         class="img-fluid rounded shadow-sm mt-3">
                </div>
            </div>

            <!-- Langkah 2 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-2-circle-fill me-2"></i>Membuka Halaman Ujian</h4>
                </div>
                <div class="card-body">
                    <p>Buka browser Anda dan akses alamat berikut:</p>
                    <div class="p-3 bg-light rounded mb-3">
                        <code class="fs-5">{{ url('/exam/join') }}</code>
                    </div>
                    <p>Atau klik tombol di bawah untuk langsung ke halaman ujian:</p>
                    <a href="{{ route('exam.join') }}" class="btn btn-gradient-primary" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Buka Halaman Ujian
                    </a>
                </div>
            </div>

            <!-- Langkah 3 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-3-circle-fill me-2"></i>Memasukkan Kode Ujian</h4>
                </div>
                <div class="card-body">
                    <p>Masukkan kode ujian 6 digit yang diberikan guru pada kolom yang tersedia.</p>
                    <ul>
                        <li>Pastikan kode diketik dengan benar (6 karakter)</li>
                        <li>Klik tombol "Lanjutkan" setelah memasukkan kode</li>
                        <li>Sistem akan memvalidasi kode yang Anda masukkan</li>
                    </ul>
                    <img src="{{ asset('images/guides/student/placeholder-1.jpg') }}"
                         alt="Form memasukkan kode ujian"
                         class="img-fluid rounded shadow-sm mt-3">
                </div>
            </div>

            <!-- Langkah 4 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-4-circle-fill me-2"></i>Mengisi Identitas</h4>
                </div>
                <div class="card-body">
                    <p>Setelah kode valid, Anda akan diminta mengisi identitas:</p>
                    <ul>
                        <li><strong>Nama Lengkap:</strong> Isi dengan nama lengkap Anda</li>
                        <li><strong>NIS/NISN:</strong> Nomor Induk Siswa/Nasional (opsional)</li>
                    </ul>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Penting:</strong> Pastikan nama yang Anda masukkan benar, karena akan muncul di hasil ujian.
                    </div>
                    <img src="{{ asset('images/guides/student/placeholder-2.jpg') }}"
                         alt="Form mengisi identitas"
                         class="img-fluid rounded shadow-sm mt-3">
                </div>
            </div>

            <!-- Langkah 5 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-5-circle-fill me-2"></i>Menunggu Persetujuan Guru</h4>
                </div>
                <div class="card-body">
                    <p>Setelah mengisi identitas, Anda akan masuk ke <strong>Waiting Room</strong> (ruang tunggu).</p>
                    <p>Di ruang tunggu:</p>
                    <ul>
                        <li>Tunggu hingga guru menyetujui keikutsertaan Anda</li>
                        <li>Halaman akan otomatis refresh ketika disetujui</li>
                        <li>Jangan tutup halaman browser</li>
                    </ul>
                    <div class="alert alert-info">
                        <i class="bi bi-clock-fill me-2"></i>
                        Proses persetujuan biasanya memakan waktu beberapa detik hingga beberapa menit tergantung jumlah siswa.
                    </div>
                </div>
            </div>

            <!-- Langkah 6 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-6-circle-fill me-2"></i>Memulai Ujian</h4>
                </div>
                <div class="card-body">
                    <p>Setelah disetujui, Anda akan melihat halaman konfirmasi untuk memulai ujian.</p>
                    <ul>
                        <li>Baca instruksi ujian dengan teliti</li>
                        <li>Perhatikan durasi waktu ujian</li>
                        <li>Klik tombol <strong>"Mulai Ujian"</strong> ketika siap</li>
                    </ul>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-octagon-fill me-2"></i>
                        <strong>Perhatian:</strong> Timer akan mulai berjalan setelah Anda klik "Mulai Ujian"!
                    </div>
                </div>
            </div>

            <!-- Langkah 7 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-7-circle-fill me-2"></i>Menjawab Soal Tier 1</h4>
                </div>
                <div class="card-body">
                    <p>Setiap soal memiliki dua tingkat pertanyaan:</p>
                    <h5 class="mt-4 mb-3">Tier 1 (Pertanyaan Utama)</h5>
                    <ul>
                        <li>Baca soal dengan teliti</li>
                        <li>Pilih jawaban yang menurut Anda paling benar</li>
                        <li>Tersedia 5 pilihan jawaban (A, B, C, D, E)</li>
                        <li>Setelah memilih, klik tombol <strong>"Lanjut ke Tier 2"</strong></li>
                    </ul>
                    <img src="{{ asset('images/guides/student/placeholder-3.jpg') }}"
                         alt="Contoh soal Tier 1"
                         class="img-fluid rounded shadow-sm mt-3">
                </div>
            </div>

            <!-- Langkah 8 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-8-circle-fill me-2"></i>Menjawab Soal Tier 2</h4>
                </div>
                <div class="card-body">
                    <h5 class="mb-3">Tier 2 (Pertanyaan Lanjutan)</h5>
                    <p>Tier 2 adalah pertanyaan yang menguji pemahaman Anda tentang alasan dari jawaban Tier 1.</p>
                    <ul>
                        <li>Baca pertanyaan Tier 2 dengan teliti</li>
                        <li>Pilih jawaban yang sesuai dengan pemahaman Anda</li>
                        <li>Klik tombol <strong>"Simpan Jawaban"</strong> untuk melanjutkan ke soal berikutnya</li>
                    </ul>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Penilaian:</strong> Skor tertinggi jika Tier 1 dan Tier 2 benar. Skor setengah jika salah satu benar.
                    </div>
                </div>
            </div>

            <!-- Langkah 9 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-9-circle-fill me-2"></i>Navigasi Antar Soal</h4>
                </div>
                <div class="card-body">
                    <p>Anda dapat berpindah antar soal dengan mudah:</p>
                    <ul>
                        <li><strong>Sidebar Kiri:</strong> Menampilkan nomor semua soal</li>
                        <li><strong>Klik Nomor Soal:</strong> Langsung ke soal tersebut</li>
                        <li><strong>Warna Hijau:</strong> Soal yang sudah dijawab</li>
                        <li><strong>Warna Abu:</strong> Soal yang belum dijawab</li>
                    </ul>
                    <div class="alert alert-info">
                        <i class="bi bi-eye-fill me-2"></i>
                        <strong>Tips:</strong> Perhatikan timer di bagian atas untuk memantau sisa waktu ujian.
                    </div>
                </div>
            </div>

            <!-- Langkah 10 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-check2-square me-2"></i>Menyelesaikan Ujian</h4>
                </div>
                <div class="card-body">
                    <p>Setelah semua soal dijawab atau waktu habis:</p>
                    <ul>
                        <li>Klik tombol <strong>"Selesai"</strong> di bagian bawah</li>
                        <li>Konfirmasi bahwa Anda ingin mengakhiri ujian</li>
                        <li>Jawaban akan otomatis tersimpan</li>
                    </ul>
                    <div class="alert alert-warning">
                        <i class="bi bi-hourglass-split me-2"></i>
                        <strong>Catatan:</strong> Jika waktu habis, sistem akan otomatis menyelesaikan ujian Anda.
                    </div>
                </div>
            </div>

            <!-- Langkah 11 -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-trophy-fill me-2"></i>Melihat Hasil</h4>
                </div>
                <div class="card-body">
                    <p>Setelah ujian selesai, Anda dapat melihat hasil:</p>
                    <ul>
                        <li><strong>Total Skor:</strong> Nilai total yang Anda peroleh</li>
                        <li><strong>Breakdown:</strong> Jumlah jawaban benar-benar, benar-salah, salah-benar, salah-salah</li>
                        <li><strong>Detail Jawaban:</strong> Review jawaban Anda per soal (jika diizinkan guru)</li>
                    </ul>
                    <p class="mb-0">Hasil ujian juga dapat dilihat oleh guru untuk evaluasi pembelajaran.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tips & Trik -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card bg-light">
                <div class="card-body p-4">
                    <h2 class="text-gradient mb-4">
                        <i class="bi bi-lightbulb-fill me-2"></i>Tips & Trik
                    </h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-wifi text-primary fs-3 me-3"></i>
                                <div>
                                    <h5>Koneksi Internet Stabil</h5>
                                    <p class="mb-0">Pastikan koneksi internet Anda stabil sebelum memulai ujian untuk menghindari masalah saat menyimpan jawaban.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-arrow-clockwise text-primary fs-3 me-3"></i>
                                <div>
                                    <h5>Jangan Refresh Halaman</h5>
                                    <p class="mb-0">Hindari me-refresh halaman browser saat ujian berlangsung karena dapat menyebabkan data hilang.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-clock-history text-primary fs-3 me-3"></i>
                                <div>
                                    <h5>Perhatikan Timer</h5>
                                    <p class="mb-0">Selalu perhatikan sisa waktu yang ditampilkan di bagian atas halaman ujian.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-book text-primary fs-3 me-3"></i>
                                <div>
                                    <h5>Baca Soal Teliti</h5>
                                    <p class="mb-0">Baca setiap soal dengan teliti, terutama untuk soal Tier 2 yang menguji pemahaman konsep.</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            Apa itu sistem two-tier?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Sistem two-tier adalah metode ujian diagnostik yang terdiri dari dua tingkat pertanyaan. Tier 1 menguji pengetahuan faktual, sedangkan Tier 2 menguji pemahaman konseptual dan alasan di balik jawaban Tier 1.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Bagaimana cara menghitung skor?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <ul class="mb-0">
                                <li><strong>Benar-Benar:</strong> Mendapat nilai penuh (100% poin soal)</li>
                                <li><strong>Benar-Salah atau Salah-Benar:</strong> Mendapat setengah nilai (50% poin soal)</li>
                                <li><strong>Salah-Salah:</strong> Tidak mendapat nilai (0 poin)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Bagaimana jika koneksi internet putus saat ujian?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Sistem menyimpan jawaban Anda secara otomatis setiap kali Anda menjawab soal. Jika koneksi putus, hubungkan kembali internet Anda dan reload halaman. Jawaban yang sudah tersimpan tidak akan hilang.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Apakah saya bisa mengubah jawaban yang sudah disimpan?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Ya, Anda dapat kembali ke soal sebelumnya dan mengubah jawaban selama ujian belum selesai dan waktu masih tersisa. Gunakan sidebar untuk navigasi ke soal yang ingin diubah.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            Apa yang terjadi jika waktu ujian habis?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Jika waktu ujian habis, sistem akan otomatis menyimpan semua jawaban yang sudah Anda kerjakan dan mengakhiri sesi ujian. Anda akan langsung diarahkan ke halaman hasil ujian.
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
                    <h3 class="mb-3">Siap Memulai Ujian?</h3>
                    <p class="lead mb-4">Jika Anda sudah memiliki kode ujian, klik tombol di bawah untuk memulai.</p>
                    <a href="{{ route('exam.join') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-pencil-square me-2"></i>Mulai Ujian Sekarang
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

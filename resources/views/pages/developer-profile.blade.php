@extends('layouts.public')

@section('title', 'Tentang Pengembang - Mustayawain')
@section('description', 'Informasi tentang pengembang sistem ujian Mustayawain dan latar belakang proyek penelitian dari Universitas Negeri Malang.')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1><i class="bi bi-people-fill me-3"></i>Tentang Pengembang</h1>
                <p class="lead mb-0">Tim di balik sistem ujian diagnostik two-tier</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Latar Belakang Proyek -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-block p-3 bg-light rounded-circle mb-3">
                            <i class="bi bi-mortarboard-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="text-gradient mb-3">Latar Belakang Proyek</h2>
                    </div>

                    <p class="lead text-center mb-4">
                        Mustayawain adalah produk dari penelitian thesis program Magister (S2)
                        Pendidikan Bahasa Arab di Universitas Negeri Malang.
                    </p>

                    <div class="row mt-5">
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="bi bi-lightbulb-fill text-primary fs-3"></i>
                                </div>
                                <div>
                                    <h5>Tujuan Penelitian</h5>
                                    <p class="text-muted mb-0">
                                        Mengembangkan sistem ujian diagnostik two-tier untuk mengidentifikasi
                                        miskonsepsi siswa dalam pembelajaran Bahasa Arab di Madrasah Aliyah.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="bi bi-trophy-fill text-primary fs-3"></i>
                                </div>
                                <div>
                                    <h5>Inovasi</h5>
                                    <p class="text-muted mb-0">
                                        Mengadaptasi metode two-tier test yang sebelumnya digunakan dalam
                                        sains ke dalam pembelajaran bahasa Arab dengan pendekatan digital.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="bi bi-graph-up-arrow text-primary fs-3"></i>
                                </div>
                                <div>
                                    <h5>Dampak</h5>
                                    <p class="text-muted mb-0">
                                        Membantu guru mengidentifikasi kesalahan konsep siswa secara lebih
                                        akurat sehingga pembelajaran dapat disesuaikan dengan kebutuhan.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded p-3 me-3">
                                    <i class="bi bi-building text-primary fs-3"></i>
                                </div>
                                <div>
                                    <h5>Institusi</h5>
                                    <p class="text-muted mb-0">
                                        Universitas Negeri Malang<br>
                                        <small>Program Studi S2 Pendidikan Bahasa Arab</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tim Pengembang -->
    <div class="row mb-5">
        <div class="col-lg-12 mx-auto">
            <h2 class="text-gradient text-center mb-5">
                <i class="bi bi-people me-2"></i>Tim Pengembang
            </h2>

            <div class="row g-4">
                <!-- Developer 1: Mahasiswa S2 -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center p-5">

                            <div style="width: 200px; height: 200px; overflow: hidden; border-radius: 50%;"
                                class="shadow mx-auto mb-4">
                                <img src="{{ asset('images/guides/developers/dev1.webp') }}"
                                    alt="Foto Siti Zulfa Hidayatul Maula"
                                    class="img-fluid"
                                    style="width: 100%; height: 100%; object-fit: cover;
                                            object-position: center 23%; transform: scale(1);">
                            </div>
                            <h3 class="mb-2">Siti Zulfa Hidayatul Maula, S.Hum., S.Ag</h3>
                            <p class="text-primary fw-bold mb-3">Peneliti & Pengembang Sistem</p>

                            <div class="text-start mt-4">
                                <div class="mb-3">
                                    <i class="bi bi-building me-2 text-primary"></i>
                                    <strong>Institusi:</strong><br>
                                    <span class="d-block ps-4">Universitas Negeri Malang</span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-mortarboard me-2 text-primary"></i>
                                    <strong>Program:</strong><br>
                                    <span class="d-block ps-4">S2 Pendidikan Bahasa Arab</span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-award me-2 text-primary"></i>
                                    <strong>Pendidikan:</strong><br>
                                    <span class="d-block ps-4">
                                        • Bahasa dan Sastra Arab, UIN Maulana Malik Ibrahim Malang (2022)<br>
                                        • Takhasus Fiqh dan Ushul Fiqh, Ma'had Aly Al-Zamachsyari Malang (2022)
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-journal-text me-2 text-primary"></i>
                                    <strong>Fokus Penelitian:</strong><br>
                                    <span class="d-block ps-4">Pengembangan instrumen penilaian diagnostik two-tier untuk mengidentifikasi miskonsepsi dalam pembelajaran Bahasa Arab</span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-envelope me-2 text-primary"></i>
                                    <strong>Email:</strong><br>
                                    <span class="d-block ps-4">szulfa.hm@gmail.com</span>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="text-start">
                                <h5 class="mb-3"><i class="bi bi-card-text me-2"></i>Bio Singkat</h5>
                                <p class="text-muted">
                                    Siti Zulfa Hidayatul Maula lahir di Jember pada hari Pahlawan.
                                    Pada tahun 2022, ia berhasil menyandang double degree, yaitu S.Hum sebagai lulusan
                                    Program Studi Bahasa dan Sastra Arab UIN Maulana Malik Ibrahim Malang, serta S.Ag
                                    sebagai lulusan Takhasus Fiqh dan Ushul Fiqh di Ma'had Aly Al-Zamachsyari Malang.
                                </p>
                                <p class="text-muted">
                                    Selain gemar membaca, ia juga gemar menulis karya bergenre fiksi. 
                                    Karya-karyanya berupa antologi puisi, cerpen, dan quotes telah dibukukan oleh beberapa penerbit. 
                                    Saat ini, ia sedang menyelesaikan tugas akhir pendidikan magister di program studi Pendidikan Bahasa Arab Universitas Negeri Malang.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Developer 2: Software Developer -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center item-center p-5">
                            <div style="width: 200px; height: 200px; overflow: hidden; border-radius: 50%;"
                                class="shadow mx-auto mb-4">
                                <img src="{{ asset('images/guides/developers/dev2.webp') }}"
                                    alt="Foto Ahmad Arfan Arsyad"
                                    class="img-fluid"
                                    style="width: 100%; height: 100%; object-fit: cover;
                                            object-position: center 45%; transform: scale(1.33);">
                            </div>
                            <h3 class="mb-2">Ahmad Arfan Arsyad, S.Tr.Stat</h3>
                            <p class="text-primary fw-bold mb-3">Software Developer</p>

                            <div class="text-start mt-4">
                                <div class="mb-3">
                                    <i class="bi bi-mortarboard me-2 text-primary"></i>
                                    <strong>Pendidikan:</strong><br>
                                    <span class="d-block ps-4">Politeknik Statistika STIS (2022)</span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-briefcase me-2 text-primary"></i>
                                    <strong>Pekerjaan:</strong><br>
                                    <span class="d-block ps-4">PNS di Badan Pusat Statistik (BPS) Kabupaten Jayawijaya</span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-award me-2 text-primary"></i>
                                    <strong>Jabatan:</strong><br>
                                    <span class="d-block ps-4">Pranata Komputer</span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-code-slash me-2 text-primary"></i>
                                    <strong>Keahlian:</strong><br>
                                    <span class="d-block ps-4">Full Stack Web Development, Laravel, JavaScript, Database Design, Statistika</span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-star me-2 text-primary"></i>
                                    <strong>Kontribusi:</strong><br>
                                    <span class="d-block ps-4">Merancang dan mengimplementasikan arsitektur sistem,
                                    mengembangkan fitur-fitur interaktif, dan memastikan performa optimal aplikasi</span>
                                </div>
                                <div class="mb-3">
                                    <i class="bi bi-envelope me-2 text-primary"></i>
                                    <strong>Email:</strong><br>
                                    <span class="d-block ps-4">aarfanarsyad@bps.go.id</span>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="text-start">
                                <h5 class="mb-3"><i class="bi bi-card-text me-2"></i>Bio Singkat</h5>
                                <p class="text-muted">
                                    Ahmad Arfan Arsyad lahir di Jember pada awal bulan Februari. Ia lulus dari
                                    sekolah kedinasan Polstat STIS (Politeknik Statistika STIS) pada tahun 2022
                                    dengan gelar Sarjana Terapan Statistika (S.Tr.Stat).
                                </p>
                                <p class="text-muted">
                                    Saat ini bekerja sebagai Pegawai Negeri Sipil (PNS) di Badan Pusat Statistik
                                    (BPS) Kabupaten Jayawijaya dengan jabatan Pranata Komputer. Memiliki keahlian
                                    dalam pengembangan aplikasi web dan pengolahan data statistik, serta berkontribusi
                                    dalam merancang arsitektur sistem Twotier Exam dan mengimplementasikan
                                    berbagai fitur teknis dalam aplikasi ini.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Teknologi yang Digunakan -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card bg-light">
                <div class="card-body p-5">
                    <h2 class="text-gradient text-center mb-5">
                        <i class="bi bi-gear-fill me-2"></i>Teknologi yang Digunakan
                    </h2>

                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-4">
                            <div class="p-4 bg-white rounded shadow-sm h-100">
                                <i class="bi bi-code-square text-danger" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Laravel 11</h5>
                                <p class="text-muted small mb-0">PHP Framework</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="p-4 bg-white rounded shadow-sm h-100">
                                <i class="bi bi-bootstrap-fill text-primary" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Bootstrap 5</h5>
                                <p class="text-muted small mb-0">CSS Framework</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="p-4 bg-white rounded shadow-sm h-100">
                                <i class="bi bi-database-fill text-warning" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">MySQL</h5>
                                <p class="text-muted small mb-0">Database</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="p-4 bg-white rounded shadow-sm h-100">
                                <i class="bi bi-filetype-js text-success" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">JavaScript</h5>
                                <p class="text-muted small mb-0">Interactivity</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <h5><i class="bi bi-server me-2 text-primary"></i>Backend</h5>
                            <ul class="list-unstyled ms-4">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Laravel 11</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>PHP 8.2+</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>MySQL Database</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>RESTful API</li>
                            </ul>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5><i class="bi bi-laptop me-2 text-primary"></i>Frontend</h5>
                            <ul class="list-unstyled ms-4">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Bootstrap 5</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Vanilla JavaScript</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Blade Templates</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Responsive Design</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fitur Utama -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <h2 class="text-gradient text-center mb-5">
                <i class="bi bi-stars me-2"></i>Fitur Utama Sistem
            </h2>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-light rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-layers-fill text-primary fs-1"></i>
                            </div>
                            <h5>Two-Tier Testing</h5>
                            <p class="text-muted small">
                                Sistem ujian dua tingkat untuk mengidentifikasi pemahaman konseptual siswa
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-light rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-clock-history text-primary fs-1"></i>
                            </div>
                            <h5>Real-time Monitoring</h5>
                            <p class="text-muted small">
                                Pantau progress siswa secara langsung selama ujian berlangsung
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-light rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-graph-up text-primary fs-1"></i>
                            </div>
                            <h5>Analisis Mendalam</h5>
                            <p class="text-muted small">
                                Analisis butir soal dan identifikasi miskonsepsi siswa secara otomatis
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-light rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-shield-check text-primary fs-1"></i>
                            </div>
                            <h5>Keamanan Data</h5>
                            <p class="text-muted small">
                                Sistem autentikasi dan otorisasi yang aman untuk melindungi data
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-light rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-phone text-primary fs-1"></i>
                            </div>
                            <h5>Responsive Design</h5>
                            <p class="text-muted small">
                                Dapat diakses dari berbagai perangkat: PC, tablet, dan smartphone
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-light rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-file-earmark-arrow-down text-primary fs-1"></i>
                            </div>
                            <h5>Export Hasil</h5>
                            <p class="text-muted small">
                                Export hasil ujian ke Excel dan PDF untuk dokumentasi
                            </p>
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
                    <h3 class="mb-3">Tertarik Menggunakan Sistem Ini?</h3>
                    <p class="lead mb-4">
                        Sistem ini dikembangkan untuk membantu pendidikan Bahasa Arab di Indonesia.
                        Hubungi kami untuk informasi lebih lanjut.
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('guide.teacher') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-book me-2"></i>Panduan Guru
                        </a>
                        <a href="{{ route('guide.student') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-book me-2"></i>Panduan Siswa
                        </a>
                    </div>
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
</style>
@endpush

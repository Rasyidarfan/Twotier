<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Mustayawain')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'Sistem ujian diagnostik two-tier untuk pembelajaran Bahasa Arab')">
    <meta name="keywords" content="ujian, mustayawain, two-tier, bahasa arab, madrasah, diagnostik">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: var(--primary-gradient);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .navbar-custom .nav-link:hover {
            color: rgba(255,255,255,0.8) !important;
        }

        .btn-gradient-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .hero-section {
            background: var(--primary-gradient);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }

        .hero-section h1 {
            font-weight: 700;
            font-size: 2.5rem;
        }

        main {
            flex: 1;
        }

        footer {
            background-color: #2d3748;
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        footer a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: white;
        }

        .footer-section-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: white;
        }

        .text-gradient {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 1.75rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="bi bi-mortarboard-fill me-2 fs-4"></i>
                <span class="fw-bold">Mustayawain</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('exam.join') }}">
                            <i class="bi bi-pencil-square me-1"></i> Ikut Ujian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guide.student') ? 'active' : '' }}" href="{{ route('guide.student') }}">
                            <i class="bi bi-book me-1"></i> Panduan Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('guide.teacher') ? 'active' : '' }}" href="{{ route('guide.teacher') }}">
                            <i class="bi bi-mortarboard me-1"></i> Panduan Guru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about.developer') ? 'active' : '' }}" href="{{ route('about.developer') }}">
                            <i class="bi bi-people me-1"></i> Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="footer-section-title">
                        <i class="bi bi-mortarboard-fill me-2"></i>
                        Twotier Exam System
                    </h5>
                    <p class="text-muted mb-0">
                        Sistem ujian diagnostik two-tier untuk pembelajaran Bahasa Arab di Madrasah Aliyah.
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="footer-section-title">Panduan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('guide.student') }}">
                                <i class="bi bi-arrow-right-short"></i> Panduan Siswa
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('guide.teacher') }}">
                                <i class="bi bi-arrow-right-short"></i> Panduan Guru
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('exam.join') }}">
                                <i class="bi bi-arrow-right-short"></i> Ikut Ujian
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('login') }}">
                                <i class="bi bi-arrow-right-short"></i> Login Guru
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="footer-section-title">Informasi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('about.developer') }}">
                                <i class="bi bi-arrow-right-short"></i> Tentang Pengembang
                            </a>
                        </li>
                        <li class="mb-2 text-muted">
                            <i class="bi bi-geo-alt"></i> Universitas Negeri Malang
                        </li>
                        <li class="mb-2 text-muted">
                            <i class="bi bi-mortarboard"></i> Penelitian Thesis S2
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light opacity-25">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="text-muted mb-0">
                        &copy; {{ date('Y') }} Twotier Exam System. Dikembangkan untuk Madrasah Aliyah.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts')
</body>
</html>

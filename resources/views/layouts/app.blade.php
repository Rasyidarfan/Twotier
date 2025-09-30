<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Ujian Bahasa Arab - Madrasah Aliyah')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .arabic-text {
            font-family: 'Amiri', 'Noto Sans Arabic', serif;
            font-size: 1.2em;
            line-height: 1.8;
            direction: rtl;
            text-align: right;
        }
        
        .arabic-option {
            font-family: 'Amiri', 'Noto Sans Arabic', serif;
            font-size: 1.1em;
            line-height: 1.6;
            direction: rtl;
            text-align: right;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.75rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-1px);
        }
        
        .timer {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: bold;
        }
        
        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .question-card {
            border-left: 4px solid #667eea;
        }
        
        .option-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .option-card:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        
        .option-card.selected {
            background-color: #e3f2fd;
            border-color: #2196f3;
        }
        
        .result-badge {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        
        .result-benar-benar { background-color: #d4edda; color: #155724; }
        .result-benar-salah { background-color: #fff3cd; color: #856404; }
        .result-salah-benar { background-color: #f8d7da; color: #721c24; }
        .result-salah-salah { background-color: #f5c6cb; color: #721c24; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            @auth
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <div class="text-center mb-4">
                            <i class="text-white bi bi-mortarboard-fill"></i>
                            <h5 class="text-white">
                                الإختبار التشخيصي
                            </h5>
                            <h5 class="text-white">
                                ذو المستويين
                            </h5>
                            <small class="text-white-50">للمدرسة الثانوية الإسلامية</small>
                        </div>
                        
                        <div class="text-center mb-3">
                            <div class="text-white">
                                <i class="bi bi-person-circle fs-3"></i>
                                <div class="mt-2">
                                    <strong>{{ auth()->user()->name }}</strong>
                                    <br>
                                    <small class="text-white-50">{{ ucfirst(auth()->user()->role) }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <ul class="nav flex-column">
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                                        <i class="bi bi-people"></i> Kelola User
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.questions*') ? 'active' : '' }}" href="{{ route('admin.questions.index') }}">
                                        <i class="bi bi-question-circle"></i> Kelola Soal
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.subjects*') ? 'active' : '' }}" href="{{ route('admin.subjects.index') }}">
                                        <i class="bi bi-book"></i> Mata Pelajaran
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.chapters*') ? 'active' : '' }}" href="{{ route('admin.chapters.index') }}">
                                        <i class="bi bi-bookmark"></i> Bab Materi
                                    </a>
                                </li>
                            @endif
                            
                            @if(auth()->user()->isGuru())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}" href="{{ route('guru.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Dashboard Guru
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('guru.exams*') ? 'active' : '' }}" href="{{ route('guru.exams.index') }}">
                                        <i class="bi bi-clipboard-check"></i> Kelola Ujian
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('guru.questions*') ? 'active' : '' }}" href="{{ route('guru.questions.index') }}">
                                        <i class="bi bi-collection"></i> Bank Soal
                                    </a>
                                </li>
                            @endif
                        </ul>
                        
                        <hr class="text-white-50">
                        
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link border-0 bg-transparent text-start w-100">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
                
                <!-- Main content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @else
                <!-- Full width for guest pages -->
                <main class="col-12">
            @endauth
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
            
            </main>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Toastr JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    @stack('scripts')
</body>
</html>

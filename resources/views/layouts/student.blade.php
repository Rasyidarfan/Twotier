<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Ujian Online')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    
    <!-- Custom Student CSS -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }
        
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
        
        /* Student-specific styles */
        .student-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .exam-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 0.75rem;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border: none;
            border-radius: 0.75rem;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            border: none;
            border-radius: 0.75rem;
        }
        
        .form-control {
            border-radius: 0.75rem;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .alert {
            border: none;
            border-radius: 1rem;
            padding: 1rem 1.5rem;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }
        
        /* Avatar styles */
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }
        
        .avatar-lg {
            width: 64px;
            height: 64px;
            font-size: 1.5rem;
        }
        
        .avatar-xl {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .card {
                margin: 0.5rem;
            }
        }
        
        /* Exam-specific styles */
        .question-nav-btn {
            margin: 0.1rem;
            min-width: 40px;
            min-height: 40px;
        }
        
        .question-container {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .tier-section {
            transition: all 0.3s ease;
        }
        
        .tier-section:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Timer styles */
        .timer {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: bold;
        }

        .timer-warning { color: #ffc107 !important; }
        .timer-danger { color: #dc3545 !important; }
        
        /* Progress bar */
        .progress {
            height: 8px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.3);
        }

        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        /* Two-tier result badges */
        .result-badge {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        .result-benar-benar { background-color: #d4edda; color: #155724; }
        .result-benar-salah { background-color: #fff3cd; color: #856404; }
        .result-salah-benar { background-color: #f8d7da; color: #721c24; }
        .result-salah-salah { background-color: #f5c6cb; color: #721c24; }

        /* Question card styling */
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
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Main Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Toast Container -->
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <!-- Toasts will be dynamically added here -->
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- Global JavaScript configuration -->
    <script>
        // Configure toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global error handler
        $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
            if (jqxhr.status === 419) {
                toastr.error('Sesi telah berakhir. Silakan refresh halaman.');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        });

        // Prevent backspace navigation
        $(document).keydown(function(e) {
            if (e.keyCode === 8 && !$(e.target).is("input, textarea")) {
                e.preventDefault();
            }
        });

        // Disable right-click context menu (optional security measure)
        $(document).contextmenu(function(e) {
            // Uncomment to disable right-click
            // e.preventDefault();
        });

        // Page visibility API for detecting tab switches
        let hidden, visibilityChange;
        if (typeof document.hidden !== "undefined") {
            hidden = "hidden";
            visibilityChange = "visibilitychange";
        } else if (typeof document.msHidden !== "undefined") {
            hidden = "msHidden";
            visibilityChange = "msvisibilitychange";
        } else if (typeof document.webkitHidden !== "undefined") {
            hidden = "webkitHidden";
            visibilityChange = "webkitvisibilitychange";
        }

        // Log when user switches tabs (for monitoring purposes)
        if (typeof document.addEventListener !== "undefined" && hidden !== undefined) {
            document.addEventListener(visibilityChange, function() {
                if (document[hidden]) {
                    console.log('Tab switched away - timestamp: ' + new Date().toISOString());
                } else {
                    console.log('Tab focused - timestamp: ' + new Date().toISOString());
                }
            }, false);
        }

        // Auto-detect timezone
        function getClientTimezone() {
            try {
                return Intl.DateTimeFormat().resolvedOptions().timeZone;
            } catch (e) {
                return 'Asia/Jakarta'; // Default fallback
            }
        }

        // Utility function to format time
        function formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            if (hours > 0) {
                return String(hours).padStart(2, '0') + ':' + 
                       String(minutes).padStart(2, '0') + ':' + 
                       String(secs).padStart(2, '0');
            } else {
                return String(minutes).padStart(2, '0') + ':' + 
                       String(secs).padStart(2, '0');
            }
        }

        // Show loading state
        function showLoading(element) {
            const originalText = $(element).html();
            $(element).data('original-text', originalText);
            $(element).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
            $(element).prop('disabled', true);
        }

        // Hide loading state
        function hideLoading(element) {
            const originalText = $(element).data('original-text');
            $(element).html(originalText);
            $(element).prop('disabled', false);
        }
    </script>
    
    @stack('scripts')
    
    <!-- Show flash messages as toasts -->
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif
    
    @if(session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif
    
    @if(session('info'))
        <script>
            toastr.info("{{ session('info') }}");
        </script>
    @endif
    
    @if(session('warning'))
        <script>
            toastr.warning("{{ session('warning') }}");
        </script>
    @endif
</body>
</html>
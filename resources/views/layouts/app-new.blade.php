<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Platform Ujian Online')</title>
    
    {{-- 1. ASSETS (CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Alpine.js (Global) --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    
    <style>
        :root {
            --app-primary: #9D4E75;
            --app-primary-hover: #7a3b5a; 
            --app-secondary: #E8B4D0;
            --app-bg: #FFF5F9;
            --app-bg-alt: #FFEEF7;
            --app-text: #444444;
            --app-text-muted: #888888;
            --card-shadow: 0 8px 30px rgba(157, 78, 117, 0.1);
            --card-hover-shadow: 0 12px 40px rgba(157, 78, 117, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            background: linear-gradient(135deg, var(--app-bg) 0%, var(--app-bg-alt) 100%);
            font-family: 'Poppins', sans-serif;
            color: var(--app-text);
            min-height: 100vh;
            display: flex; flex-direction: column;
            padding-top: 80px; 
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: ''; position: fixed; top: -150px; right: -150px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(157, 78, 117, 0.08) 0%, transparent 70%);
            border-radius: 50%; z-index: -1;
        }
        body::after {
            content: ''; position: fixed; bottom: -200px; left: -200px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(157, 78, 117, 0.06) 0%, transparent 70%);
            border-radius: 50%; z-index: -1;
        }

        /* Navbar Glassmorphism */
        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(157, 78, 117, 0.08);
            border-bottom-left-radius: 30px; border-bottom-right-radius: 30px;
            padding: 0.8rem 0; z-index: 1030;
        }
        .navbar-brand { 
            color: var(--app-primary) !important; font-weight: 700; font-size: 1.3rem; 
            display: flex; align-items: center;
        }
        
        /* User Badge (Pojok Kanan Atas) */
        .user-badge {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            color: white; padding: 6px 18px; border-radius: 50px;
            font-size: 0.85rem; font-weight: 500; display: inline-block;
            box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
        }

        /* Tombol Logout Merah */
        .btn-danger-custom {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none; border-radius: 50px; padding: 6px 20px;
            font-size: 0.85rem; font-weight: 500; color: white;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
            transition: all 0.3s;
        }
        .btn-danger-custom:hover {
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4); color: white; transform: translateY(-1px);
        }

        /* Tombol Primary Global */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            border: none; border-radius: 50px; padding: 12px 40px; color: white;
            font-weight: 600; box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
            transition: all 0.3s;
        }
        .btn-primary-custom:hover { 
            transform: translateY(-2px); box-shadow: 0 6px 20px rgba(157, 78, 117, 0.4); color: white;
        }

        /* Cards Global */
        .card-custom {
            border: none; border-radius: 25px; box-shadow: var(--card-shadow);
            background: white; transition: all 0.3s ease; overflow: hidden;
        }
        .card-custom:hover { box-shadow: var(--card-hover-shadow); }

        /* Alerts */
        .alert { border-radius: 20px; border: none; padding: 1rem 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .alert-success { background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); color: #155724; }
        .alert-danger { background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); color: #721c24; }

        /* Footer */
        footer {
            background: white; border-top-left-radius: 30px; border-top-right-radius: 30px;
            box-shadow: 0 -4px 20px rgba(157, 78, 117, 0.08); margin-top: auto; width: 100%;
            padding: 20px 0; text-align: center; color: var(--app-text-muted);
        }

        /* Animasi Fade In */
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                {{-- Anda bisa ubah teks/icon ini sesuai keinginan global --}}
                <i class="bi bi-mortarboard-fill me-2"></i>Platform Ujian
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    @auth
                        <div class="user-badge">
                            <i class="bi bi-person-circle me-2"></i>{{ Auth::user()->name }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger-custom btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    @else
                        {{-- Jika belum login (opsional) --}}
                        <a href="{{ route('login') }}" class="btn btn-primary-custom btn-sm px-4 py-2">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- 4. MAIN CONTENT WRAPPER --}}
    <main class="container my-5">
        
        {{-- Global Alerts (Akan muncul otomatis jika ada session status/error) --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show fade-in-up mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> 
                <strong>Berhasil!</strong> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show fade-in-up mb-4" role="alert">
                <p class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kesalahan:</p>
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="fade-in-up">
            @yield('content')
        </div>

    </main>

    <footer class="text-center py-4">
        <p>&copy; {{ date('Y') }} Platform Ujian Online. All rights reserved.</p>
    </footer>

    {{-- 6. SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Tempat untuk script tambahan dari halaman anak --}}
    @stack('scripts')
</body>
</html>
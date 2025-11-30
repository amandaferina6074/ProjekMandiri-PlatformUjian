<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - {{ config('app.name', 'SuksesUjian') }}</title>
    
    {{-- Bootstrap 5 & Icons (Sesuai Referensi) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {{-- Font modern --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
    
        :root {
            --app-primary: #9D4E75;
            --app-primary-hover: #7a3b5a; 
            --app-secondary: #E8B4D0;
            --app-bg: #FFF5F9;
            --app-bg-alt: #FFEEF7;
            --app-text: #444444;
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
            overflow-x: hidden;
        }

        /* Navbar Style */
        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(157, 78, 117, 0.08);
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            padding: 1rem 0;
            z-index: 1000;
        }
        .navbar-brand { color: var(--app-primary) !important; font-weight: 700; font-size: 1.4rem; }

        /* Hero Section */
        .hero-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
        }

        .hero-card {
            background: white;
            border-radius: 30px;
            padding: 50px;
            box-shadow: var(--card-shadow);
            max-width: 900px;
            width: 100%;
            border-top: 6px solid var(--app-primary);
            position: relative;
            overflow: hidden;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--app-primary);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-text {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        /* Buttons (Adapted from .btn-add) */
        .btn-hero-primary {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            color: white; border: none; border-radius: 50px; padding: 12px 35px;
            font-weight: 600; box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
            display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
            transition: all 0.3s;
            font-size: 1rem;
        }
        .btn-hero-primary:hover { 
            color: white; transform: translateY(-3px); 
            box-shadow: 0 8px 25px rgba(157, 78, 117, 0.4); 
        }

        .btn-hero-outline {
            background: white;
            color: var(--app-primary); 
            border: 2px solid var(--app-secondary);
            border-radius: 50px; padding: 10px 35px;
            font-weight: 600; 
            display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
            transition: all 0.3s;
            font-size: 1rem;
        }
        .btn-hero-outline:hover { 
            background-color: var(--app-bg);
            border-color: var(--app-primary);
            color: var(--app-primary-hover);
            transform: translateY(-3px); 
        }

        /* Decorations */
        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--app-secondary) 0%, var(--app-bg) 100%);
            opacity: 0.3;
            z-index: 0;
        }
        
        .fade-in-up { animation: fadeInUp 0.8s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        .icon-hero {
            font-size: 4rem;
            color: var(--app-primary);
            opacity: 0.8;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>SuksesUjian
            </a>
            
            {{-- Optional: Tampilkan link Login di navbar jika mobile --}}
            <div class="d-flex d-lg-none">
                 @if (Route::has('login'))
                    @auth
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">Masuk</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="hero-container">
        {{-- Background Decoration --}}
        <div class="decoration-circle" style="width: 300px; height: 300px; top: -50px; left: -100px;"></div>
        <div class="decoration-circle" style="width: 200px; height: 200px; bottom: 50px; right: -50px;"></div>

        <div class="hero-card fade-in-up text-center">
            
            <div class="mb-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3" style="width: 120px; height: 120px;">
                    <i class="bi bi-journal-check icon-hero m-0"></i>
                </div>
            </div>

            <h1 class="hero-title">Platform Ujian Online <br> Modern & Aman</h1>
            
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <p class="hero-text">
                        Sistem manajemen ujian terpadu untuk Dosen dan Mahasiswa. 
                        Kelola soal, jadwal, dan penilaian dengan tampilan yang bersih, 
                        mudah digunakan, dan elegan.
                    </p>
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mt-2">
                @if (Route::has('login'))
                    @auth
                        {{-- Logika Redirect Dashboard (Sesuai kode awal Anda) --}}
                        @php
                            $dashboardRoute = match(auth()->user()->role) {
                                'admin' => url('/admin/dashboard'),
                                'dosen' => url('/ujian'),
                                default => url('/dashboard'),
                            };
                        @endphp

                        <a href="{{ $dashboardRoute }}" class="btn-hero-primary">
                            <i class="bi bi-speedometer2"></i> Ke Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-hero-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-hero-outline">
                                <i class="bi bi-person-plus"></i> Daftar Akun
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <div class="mt-5 pt-3 border-top">
                <small class="text-muted">
                    &copy; {{ date('Y') }} Platform Ujian Online. Didesain dengan <i class="bi bi-heart-fill text-danger" style="font-size: 0.7rem;"></i> untuk Pendidikan.
                </small>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
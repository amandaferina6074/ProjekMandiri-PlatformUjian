<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Profil Saya - Platform Ujian</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- STYLE TEMA APLIKASI --- */
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
        }

        body::before {
            content: ''; position: fixed; top: -150px; right: -150px; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(157, 78, 117, 0.08) 0%, transparent 70%);
            border-radius: 50%; z-index: -1;
        }

        /* Navbar */
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
        
        .user-badge {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            color: white; padding: 8px 20px; border-radius: 50px;
            font-size: 0.85rem; font-weight: 500; display: inline-block;
            box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
        }

        /* Footer */
        footer {
            background: white; border-top-left-radius: 30px; border-top-right-radius: 30px;
            box-shadow: 0 -4px 20px rgba(157, 78, 117, 0.08); margin-top: auto; padding: 1.5rem 0;
        }

        /* Card Styles */
        .card {
            border: none; border-radius: 25px; box-shadow: var(--card-shadow);
            background: white; transition: all 0.3s ease; margin-bottom: 2rem;
            overflow: hidden;
        }
        .card-header {
            background: white; border-bottom: 1px solid #f0f0f0; padding: 1.5rem 2rem;
            font-weight: 700; color: var(--app-primary); font-size: 1.1rem;
        }
        .card-body { padding: 2rem; }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%; padding: 10px 15px; border-radius: 10px;
            border: 1px solid #ddd; margin-top: 5px; margin-bottom: 15px;
            display: block;
        }
        input:focus { outline: 2px solid var(--app-secondary); border-color: var(--app-primary); }
        
        label { font-weight: 600; font-size: 0.9rem; color: #555; }
        
        button[type="submit"], .btn-primary {
            background: var(--app-primary); color: white; border: none;
            padding: 10px 25px; border-radius: 50px; font-weight: 600;
            transition: 0.3s; cursor: pointer; text-transform: uppercase; font-size: 0.85rem;
            letter-spacing: 1px;
        }
        button[type="submit"]:hover { background: var(--app-primary-hover); transform: translateY(-2px); }

        .text-sm { font-size: 0.875rem; color: #666; }
        .mt-1 { margin-top: 0.25rem; }
        .mt-6 { margin-top: 1.5rem; }
        .gap-4 { gap: 1rem; }
    </style>
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
        <div class="container">
            {{-- Link Balik Sesuai Role --}}
            @php
                $dashboardRoute = Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard');
                if(Auth::user()->role === 'dosen') $dashboardRoute = route('ujian.index');
            @endphp

            <a class="navbar-brand" href="{{ $dashboardRoute }}">
                <i class="bi bi-arrow-left-circle-fill me-2"></i> Kembali
            </a>
            
            <div class="collapse navbar-collapse justify-content-end">
                <div class="d-flex align-items-center gap-3">
                    <div class="user-badge">
                        <i class="bi bi-person-gear me-2"></i>Pengaturan Akun
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <h3 class="fw-bold mb-4" style="color: var(--app-primary)">Profil Pengguna</h3>

                {{-- 1. Update Profil --}}
                <div class="card fade-in-up">
                    <div class="card-header">
                        <i class="bi bi-person-lines-fill me-2"></i> Informasi Pribadi
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- 2. Update Password --}}
                <div class="card fade-in-up">
                    <div class="card-header">
                        <i class="bi bi-lock-fill me-2"></i> Ganti Password
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="text-center">
        <p class="mb-0 small text-muted">&copy; {{ date('Y') }} Platform Ujian Online. All rights reserved.</p>
    </footer>
    
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
       
        .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        
     
        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
    </style>
</body>
</html>
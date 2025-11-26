<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Admin Dashboard - Platform Ujian</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
        .navbar-brand {
            color: var(--app-primary) !important; font-weight: 700; font-size: 1.4rem;
            display: flex; align-items: center; gap: 10px;
        }
        
        /* User Badge */
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
            background: white; transition: all 0.3s ease; position: relative; overflow: hidden;
        }
        .card:hover { transform: translateY(-5px); box-shadow: var(--card-hover-shadow); }

        /* Welcome Banner */
        .welcome-card {
            background: linear-gradient(135deg, var(--app-primary) 0%, #8e24aa 100%);
            color: white; border-radius: 25px; padding: 3rem; margin-bottom: 2rem;
            position: relative; overflow: hidden;
        }
        .welcome-card::after {
            content: ''; position: absolute; top: 0; right: 0; width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            border-radius: 50%; transform: translate(30%, -30%);
        }

        /* Menu Icon */
        .menu-icon-wrapper {
            width: 70px; height: 70px; border-radius: 50%;
            background-color: var(--app-bg); color: var(--app-primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin-bottom: 1rem; transition: all 0.3s;
        }
        .card:hover .menu-icon-wrapper {
            background-color: var(--app-primary); color: white; transform: scale(1.1);
        }
        .action-link { text-decoration: none; color: inherit; display: block; height: 100%; }

        /* Stats */
        .stat-card { border-left: 5px solid var(--app-primary); }
        .stat-value { font-size: 2.5rem; font-weight: 700; color: var(--app-primary); line-height: 1; }
        .stat-label { font-size: 0.9rem; color: var(--app-text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        
        /* Animations */
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-shield-lock-fill"></i>
                <span>Admin Panel</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <div class="user-badge">
                        <i class="bi bi-person-circle me-2"></i>
                        {{ Auth::user()->name }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        {{-- Welcome Banner --}}
        <div class="welcome-card fade-in-up">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">Dashboard Utama</h1>
                    <p class="mb-0 opacity-75 fs-5">Ringkasan data sistem ujian online & manajemen akses pengguna.</p>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <i class="bi bi-bar-chart-line" style="font-size: 6rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        {{-- NOTIFIKASI PERMINTAAN RESET PASSWORD (BARU) --}}
        @if(isset($resetRequests) && $resetRequests > 0)
        <div class="alert alert-warning border-0 shadow-sm rounded-4 fade-in-up d-flex align-items-center mb-4" role="alert" style="background-color: #fff3cd; color: #664d03;">
            <div class="bg-warning text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="bi bi-exclamation-lg fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0">Permintaan Reset Password Masuk!</h6>
                <p class="mb-0 small">Ada <strong>{{ $resetRequests }} pengguna</strong> yang lupa password dan meminta bantuan reset.</p>
            </div>
            <a href="{{ route('admin.users.index', ['filter_reset' => 1]) }}" class="btn btn-warning btn-sm ms-auto rounded-pill fw-bold text-dark px-3">
                Cek Sekarang <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        @endif

        <div class="row g-4">
            {{-- KIRI: Menu Navigasi Utama --}}
            <div class="col-lg-8">
                <h5 class="fw-bold mb-3" style="color: var(--app-primary)"><i class="bi bi-grid-fill me-2"></i>Menu Cepat</h5>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    
                    {{-- Menu 1: Kelola User (INTI) --}}
                    <div class="col">
                        <a href="{{ route('admin.users.index') }}" class="action-link">
                            <div class="card h-100 p-4 text-center fade-in-up" style="animation-delay: 0.1s">
                                <div class="d-flex justify-content-center">
                                    <div class="menu-icon-wrapper">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                </div>
                                <h4 class="fw-bold mb-2">Kelola User</h4>
                                <p class="text-muted small mb-0">Tambah user baru, hapus akun, atau reset password pengguna.</p>
                            </div>
                        </a>
                    </div>

                    {{-- Menu 2: Profil Admin (INTI) --}}
                    <div class="col">
                        <a href="{{ route('profile.edit') }}" class="action-link">
                            <div class="card h-100 p-4 text-center fade-in-up" style="animation-delay: 0.2s">
                                <div class="d-flex justify-content-center">
                                    <div class="menu-icon-wrapper">
                                        <i class="bi bi-person-gear"></i>
                                    </div>
                                </div>
                                <h4 class="fw-bold mb-2">Profil Saya</h4>
                                <p class="text-muted small mb-0">Update nama, email, dan keamanan akun admin Anda.</p>
                            </div>
                        </a>
                    </div>

                </div>

                {{-- Info Box --}}
                <div class="card mt-4 p-4 fade-in-up" style="animation-delay: 0.3s">
                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-info-circle-fill text-info me-2"></i>Informasi Sistem</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 border-0 d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <span>Data ujian dan soal dikelola sepenuhnya melalui akun <strong>Dosen</strong>.</span>
                        </li>
                        <li class="list-group-item px-0 border-0 d-flex align-items-start gap-2">
                            <i class="bi bi-check-circle-fill text-success mt-1"></i>
                            <span>Gunakan fitur "Kelola User" jika ada mahasiswa yang lupa password.</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- KANAN: Statistik Realtime --}}
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3" style="color: var(--app-primary)"><i class="bi bi-activity me-2"></i>Statistik Live</h5>
                
                {{-- Total User Card --}}
                <div class="card stat-card mb-3 p-3 fade-in-up" style="animation-delay: 0.4s">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Pengguna</div>
                            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                        </div>
                        <div class="bg-light rounded-circle p-3">
                            <i class="bi bi-database-fill text-primary fs-3"></i>
                        </div>
                    </div>
                </div>

                {{-- Detail Grid --}}
                <div class="row g-3 fade-in-up" style="animation-delay: 0.5s">
                    <div class="col-6">
                        <div class="card p-3 text-center h-100 border-bottom border-4 border-danger">
                            <i class="bi bi-briefcase fs-2 text-danger mb-2"></i>
                            <h3 class="fw-bold mb-0">{{ $totalDosen ?? 0 }}</h3>
                            <small class="text-muted fw-bold">Dosen</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-3 text-center h-100 border-bottom border-4 border-info">
                            <i class="bi bi-mortarboard fs-2 text-info mb-2"></i>
                            <h3 class="fw-bold mb-0">{{ $totalMahasiswa ?? 0 }}</h3>
                            <small class="text-muted fw-bold">Mahasiswa</small>
                        </div>
                    </div>
                    <div class="col-12">
                         <div class="card p-3 text-center h-100 border-bottom border-4 border-warning">
                            <div class="d-flex justify-content-between align-items-center px-3">
                                <div class="text-start">
                                    <h3 class="fw-bold mb-0">{{ $totalAdmin ?? 0 }}</h3>
                                    <small class="text-muted fw-bold">Administrator</small>
                                </div>
                                <i class="bi bi-shield-check fs-1 text-warning"></i>
                            </div>
                        </div>
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
    
    {{-- Animation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                setTimeout(() => { card.classList.add('fade-in-up'); }, index * 100);
            });
        });
    </script>
</body>
</html>
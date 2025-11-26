<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Kelola User - Admin Panel</title>
    
    {{-- Bootstrap 5 --}}
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
            position: relative;
        }

        /* Background Decoration */
        body::before {
            content: ''; position: fixed; top: -150px; right: -150px; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(157, 78, 117, 0.08) 0%, transparent 70%);
            border-radius: 50%; z-index: -1;
        }
        body::after {
            content: ''; position: fixed; bottom: -200px; left: -200px; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(157, 78, 117, 0.06) 0%, transparent 70%);
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
            font-size: 0.85rem; font-weight: 500; box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
        }

        /* Footer */
        footer {
            background: white; border-top-left-radius: 30px; border-top-right-radius: 30px;
            box-shadow: 0 -4px 20px rgba(157, 78, 117, 0.08); margin-top: auto; width: 100%;
        }

        /* --- STYLE KHUSUS HALAMAN USER --- */
        .page-header {
            margin-bottom: 2rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        
        .btn-back {
            color: var(--app-primary); text-decoration: none; font-weight: 600;
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 20px; background: white; border-radius: 50px;
            box-shadow: var(--card-shadow); transition: all 0.3s;
        }
        .btn-back:hover { transform: translateX(-5px); color: var(--app-primary-hover); }

        /* Sidebar Stats */
        .stat-card {
            background: white; border-radius: 20px; padding: 25px;
            box-shadow: var(--card-shadow); text-align: center;
            border-bottom: 4px solid var(--app-primary);
        }
        .stat-icon {
            width: 60px; height: 60px; border-radius: 50%; margin: 0 auto 15px;
            background: linear-gradient(135deg, var(--app-secondary) 0%, var(--app-primary) 100%);
            color: white; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;
        }

        /* Sidebar Search & Filter Active State */
        .input-search {
            border-radius: 50px; padding: 10px 20px; border: 1px solid #eee;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.03);
        }
        .input-search:focus {
            border-color: var(--app-secondary); box-shadow: 0 0 0 4px rgba(232, 180, 208, 0.3);
        }
        .btn-search {
            border-radius: 50px; background: var(--app-primary); color: white;
            padding: 8px 20px; border: none; transition: 0.3s;
        }
        .btn-search:hover { background: var(--app-primary-hover); }

        .filter-btn {
            width: 100%; text-align: left; border-radius: 15px; padding: 10px 15px;
            border: 1px solid transparent; margin-bottom: 8px;
            transition: all 0.3s; position: relative; overflow: hidden;
            display: flex; align-items: center;
        }
        .filter-btn:hover { background-color: var(--app-bg-alt); transform: translateX(5px); }
        
        .filter-btn.active {
            background: linear-gradient(45deg, var(--app-primary), #C05D8A);
            color: white; box-shadow: 0 5px 15px rgba(157, 78, 117, 0.3);
        }
        .filter-btn.active i { color: white !important; }

        /* User Card Item */
        .user-card {
            background: white; border-radius: 20px; padding: 20px;
            box-shadow: var(--card-shadow); transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.02); height: 100%;
            display: flex; flex-direction: column;
            position: relative;
        }
        /* Highlight kartu yang minta reset */
        .user-card.needs-reset {
            border: 2px solid #dc3545;
            background: #fff5f5;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
            border-color: var(--app-secondary);
        }
        
        .avatar-circle {
            width: 50px; height: 50px; border-radius: 50%;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white; font-weight: 700; font-size: 1.2rem;
            display: flex; align-items: center; justify-content: center;
            margin-right: 15px; flex-shrink: 0;
        }
        
        /* Role Colors */
        .role-admin .avatar-circle { background: linear-gradient(135deg, #FF512F 0%, #DD2476 100%); }
        .role-dosen .avatar-circle { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .role-mahasiswa .avatar-circle { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

        .badge-role { padding: 5px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 600; }
        .bg-role-admin { background-color: #FF512F; color: white; }
        .bg-role-dosen { background-color: #11998e; color: white; }
        .bg-role-mahasiswa { background-color: #4facfe; color: white; }

        /* Action Buttons */
        .btn-icon {
            width: 35px; height: 35px; border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            border: none; transition: all 0.2s;
        }
        .btn-icon:hover { transform: scale(1.1); }

        /* Animation */
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-shield-lock-fill me-2"></i>Admin Panel
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
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        {{-- Header Page --}}
        <div class="page-header fade-in-up">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn-back mb-2">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
                <h2 class="fw-bold text-dark">Manajemen Pengguna</h2>
                <p class="text-muted mb-0">Kelola data akses seluruh pengguna sistem.</p>
            </div>
        </div>

        {{-- Alert Notification --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 fade-in-up d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 fade-in-up d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <strong>Gagal!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Sidebar & Filter --}}
            <div class="col-lg-3">
                
                <div class="stat-card fade-in-up mb-4">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h2 class="fw-bold mb-0">{{ $users->total() }}</h2>
                    <span class="text-muted small">Total Pengguna Ditemukan</span>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-3 fade-in-up mb-4" style="animation-delay: 0.1s;">
                    <h6 class="fw-bold mb-3 text-uppercase small text-muted"><i class="bi bi-search me-2"></i>Cari Pengguna</h6>
                    <form action="{{ route('admin.users.index') }}" method="GET">
                        {{-- Jaga filter role/reset saat searching --}}
                        @if(request('role'))
                            <input type="hidden" name="role" value="{{ request('role') }}">
                        @endif
                        @if(request('filter_reset'))
                            <input type="hidden" name="filter_reset" value="1">
                        @endif
                        
                        <div class="input-group mb-2">
                            <input type="text" name="search" class="form-control input-search" placeholder="Nama/Email..." value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn btn-search w-100">Cari Data</button>
                    </form>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-3 fade-in-up" style="animation-delay: 0.2s;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-uppercase small text-muted mb-0"><i class="bi bi-funnel-fill me-2"></i>Filter Role</h6>
                        {{-- Tombol Reset Filter --}}
                        @if(request('role') || request('search') || request('filter_reset'))
                            <a href="{{ route('admin.users.index') }}" class="badge bg-secondary text-decoration-none rounded-pill">Reset</a>
                        @endif
                    </div>
                    
                    <div class="d-grid gap-1">
                        {{-- 1. Tombol Minta Reset Password (PENTING) --}}
                        <a href="{{ route('admin.users.index', ['filter_reset' => 1]) }}" 
                           class="btn filter-btn {{ request('filter_reset') ? 'active' : 'btn-light' }} text-danger fw-bold border-danger">
                            <i class="bi bi-exclamation-circle-fill {{ request('filter_reset') ? '' : 'text-danger' }} me-2"></i>
                            Minta Reset
                            @if(isset($resetCount) && $resetCount > 0)
                                <span class="badge bg-danger ms-auto rounded-pill">{{ $resetCount }}</span>
                            @endif
                        </a>

                        <hr class="my-1 text-muted opacity-25">

                        {{-- Tombol Semua --}}
                        <a href="{{ route('admin.users.index', ['search' => request('search')]) }}" 
                           class="btn filter-btn {{ (!request('role') && !request('filter_reset')) ? 'active' : 'btn-light' }}">
                            <i class="bi bi-grid-fill {{ (!request('role') && !request('filter_reset')) ? '' : 'text-muted' }} me-2"></i>Semua Role
                        </a>

                        {{-- Tombol Admin --}}
                        <a href="{{ route('admin.users.index', ['role' => 'admin', 'search' => request('search')]) }}" 
                           class="btn filter-btn {{ request('role') == 'admin' ? 'active' : 'btn-light' }}">
                            <i class="bi bi-shield-fill {{ request('role') == 'admin' ? '' : 'text-danger' }} me-2"></i>Admin
                        </a>

                        {{-- Tombol Dosen --}}
                        <a href="{{ route('admin.users.index', ['role' => 'dosen', 'search' => request('search')]) }}" 
                           class="btn filter-btn {{ request('role') == 'dosen' ? 'active' : 'btn-light' }}">
                            <i class="bi bi-person-video3 {{ request('role') == 'dosen' ? '' : 'text-success' }} me-2"></i>Dosen
                        </a>

                        {{-- Tombol Mahasiswa --}}
                        <a href="{{ route('admin.users.index', ['role' => 'mahasiswa', 'search' => request('search')]) }}" 
                           class="btn filter-btn {{ request('role') == 'mahasiswa' ? 'active' : 'btn-light' }}">
                            <i class="bi bi-mortarboard-fill {{ request('role') == 'mahasiswa' ? '' : 'text-info' }} me-2"></i>Mahasiswa
                        </a>
                    </div>
                </div>
            </div>

            {{-- User Grid List --}}
            <div class="col-lg-9">
                {{-- Menampilkan Info Pencarian jika ada --}}
                @if(request('search') || request('role') || request('filter_reset'))
                    <div class="fade-in-up mb-3">
                        <span class="text-muted">
                            Menampilkan hasil untuk: 
                            @if(request('filter_reset')) <strong class="text-danger">Permintaan Reset Password</strong> @endif
                            @if(request('role')) <strong>Role {{ ucfirst(request('role')) }}</strong> @endif
                            @if(request('search')) <strong>"{{ request('search') }}"</strong> @endif
                        </span>
                    </div>
                @endif

                @if(count($users) > 0)
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @foreach ($users as $user)
                        <div class="col">
                            <div class="user-card role-{{ $user->role }} fade-in-up {{ $user->password_reset_requested_at ? 'needs-reset' : '' }}">
                                
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="fw-bold mb-0 text-truncate" title="{{ $user->name }}">{{ $user->name }}</h6>
                                        <span class="text-muted small text-truncate d-block">{{ $user->email }}</span>
                                    </div>
                                </div>
                                
                                {{-- 2. Tanda Jika Minta Reset Password --}}
                                @if($user->password_reset_requested_at)
                                    <div class="alert alert-danger py-2 px-3 mb-3 d-flex align-items-center gap-2 small border-0 bg-danger-subtle text-danger fw-bold rounded-3">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        Meminta Reset Password
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <div>
                                        @if($user->role == 'admin') 
                                            <span class="badge badge-role bg-role-admin">Admin</span>
                                        @elseif($user->role == 'dosen') 
                                            <span class="badge badge-role bg-role-dosen">Dosen</span>
                                        @else 
                                            <span class="badge badge-role bg-role-mahasiswa">Mahasiswa</span>
                                        @endif
                                    </div>

                                    <div class="d-flex gap-2">
                                        {{-- 3. Reset Password Button --}}
                                        @if(Route::has('admin.users.reset'))
                                        <form action="{{ route('admin.users.reset', $user->id) }}" method="POST" onsubmit="return confirm('Reset password user ini menjadi default?');">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn-icon {{ $user->password_reset_requested_at ? 'btn-danger pulse-animation' : 'btn-light text-warning' }}" 
                                                    title="{{ $user->password_reset_requested_at ? 'SEGERA RESET PASSWORD' : 'Reset Password' }}">
                                                <i class="bi bi-key-fill"></i>
                                            </button>
                                        </form>
                                        @endif

                                        {{-- Delete Button --}}
                                        @if(Route::has('admin.users.destroy'))
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('⚠️ PERINGATAN: Hapus user ini secara permanen?\n\nNama: {{ $user->name }}\n\nData yang dihapus tidak bisa dikembalikan!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-light text-danger" title="Hapus User">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-5 fade-in-up bg-white rounded-4 shadow-sm">
                        <i class="bi bi-search text-muted opacity-50" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold mt-3 text-muted">Data Tidak Ditemukan</h5>
                        <p class="text-muted mb-3">Tidak ada pengguna yang cocok dengan pencarian/filter Anda.</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Reset Pencarian</a>
                    </div>
                @endif
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="text-center py-4">
        <p>&copy; {{ date('Y') }} Platform Ujian Online. All rights reserved.</p>
    </footer>
    
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animasi fade-in
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in-up');
            elements.forEach((el, index) => {
                el.style.animationDelay = (index * 0.05) + 's';
            });
        });
    </script>
    <style>
        .pulse-animation {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
    </style>
</body>
</html>
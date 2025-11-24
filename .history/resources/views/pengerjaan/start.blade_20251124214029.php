<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Mulai Ujian: {{ $ujian->judul }}</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- STYLE TEMA UTAMA --- */
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

        /* Card Info */
        .start-card {
            background: white; border-radius: 25px;
            box-shadow: var(--card-shadow); overflow: hidden;
            border-top: 5px solid var(--app-primary);
            transition: transform 0.3s;
        }
        
        .exam-icon {
            width: 80px; height: 80px; background: var(--app-bg);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; color: var(--app-primary); margin: 0 auto 1.5rem;
        }

        .info-badge {
            padding: 8px 20px; border-radius: 50px; font-weight: 600; font-size: 0.9rem;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .badge-time { background: #e3f2fd; color: #0d47a1; }
        .badge-count { background: #f3e5f5; color: #7b1fa2; }

        /* Form Inputs */
        .input-token {
            border: 2px solid #e0e0e0; border-radius: 15px;
            padding: 15px; font-size: 1.5rem; letter-spacing: 5px;
            text-align: center; text-transform: uppercase; font-weight: 700;
            color: var(--app-primary); background: #fafafa; transition: all 0.3s;
        }
        .input-token:focus {
            border-color: var(--app-primary); background: white;
            box-shadow: 0 0 0 5px rgba(157, 78, 117, 0.1); outline: none;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            border: none; border-radius: 50px; padding: 15px 40px;
            font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3); transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-3px); box-shadow: 0 8px 25px rgba(157, 78, 117, 0.4);
        }
        
        .btn-back {
            color: var(--app-text); text-decoration: none; font-weight: 600;
            display: inline-flex; align-items: center; gap: 5px; transition: 0.3s;
        }
        .btn-back:hover { color: var(--app-primary); transform: translateX(-5px); }

        /* Footer */
        footer {
            background: white; border-top-left-radius: 30px; border-top-right-radius: 30px;
            box-shadow: 0 -4px 20px rgba(157, 78, 117, 0.08); margin-top: auto; width: 100%;
        }

        /* Animation */
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>Mahasiswa Panel
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <div class="user-badge">
                        <i class="bi bi-person-circle me-2"></i>{{ Auth::user()->name }}
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
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="mb-4 fade-in-up">
                    <a href="{{ route('dashboard') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>

                <div class="start-card p-5 text-center fade-in-up" style="animation-delay: 0.1s;">
                    <div class="exam-icon">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    
                    <h2 class="fw-bold text-dark mb-2">{{ $ujian->judul }}</h2>
                    <p class="text-muted mb-4">{{ $ujian->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}</p>

                    <div class="d-flex justify-content-center gap-3 mb-4 flex-wrap">
                        <span class="info-badge badge-time">
                            <i class="bi bi-stopwatch-fill"></i> {{ $ujian->durasi_menit }} Menit
                        </span>
                        <span class="info-badge badge-count">
                            <i class="bi bi-list-ol"></i> {{ $ujian->soals_count }} Soal
                        </span>
                    </div>

                    <div class="alert alert-warning border-0 bg-warning-subtle text-warning-emphasis rounded-4 mb-4 text-start small">
                        <div class="d-flex">
                            <i class="bi bi-exclamation-triangle-fill fs-5 me-3"></i>
                            <div>
                                <strong>Penting:</strong>
                                <ul class="mb-0 ps-3 mt-1">
                                    <li>Waktu akan otomatis berjalan setelah tombol "Mulai" ditekan.</li>
                                    <li>Pastikan koneksi internet Anda stabil.</li>
                                    <li>Jangan me-refresh halaman saat ujian berlangsung.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('pengerjaan.begin', $ujian) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="token" class="form-label fw-bold text-muted small text-uppercase">Konfirmasi Token Ujian</label>
                            <input type="text" 
                                   name="token" 
                                   id="token" 
                                   class="form-control input-token @error('token') is-invalid @enderror" 
                                   placeholder="X Y Z 1 2 3" 
                                   required 
                                   autocomplete="off">
                                   
                            @error('token')
                                <div class="text-danger mt-2 fw-bold">
                                    <i class="bi bi-x-circle-fill me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Apakah Anda yakin ingin memulai ujian sekarang? Waktu tidak dapat dihentikan.');">
                                MULAI MENGERJAKAN <i class="bi bi-play-circle-fill ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>

    <footer class="text-center py-4">
        <p>&copy; {{ date('Y') }} Platform Ujian Online. All rights reserved.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
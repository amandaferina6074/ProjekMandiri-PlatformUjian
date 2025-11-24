<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Hasil Ujian - {{ $ujian->judul }}</title>
    
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

        /* Result Card */
        .result-card {
            background: white; border-radius: 30px; padding: 3rem;
            box-shadow: var(--card-shadow); text-align: center;
            position: relative; overflow: hidden; border-top: 6px solid var(--app-primary);
        }
        
        /* Confetti Effect (Static Decoration) */
        .result-card::before, .result-card::after {
            content: ''; position: absolute; width: 150px; height: 150px;
            border-radius: 50%; opacity: 0.1; z-index: 0;
        }
        .result-card::before { top: -50px; left: -50px; background: var(--app-primary); }
        .result-card::after { bottom: -50px; right: -50px; background: var(--app-secondary); }

        .icon-wrapper {
            width: 100px; height: 100px; border-radius: 50%;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724; display: flex; align-items: center; justify-content: center;
            font-size: 3.5rem; margin: 0 auto 1.5rem; position: relative; z-index: 1;
            box-shadow: 0 10px 20px rgba(21, 87, 36, 0.15);
        }

        /* Score Typography */
        .score-big {
            font-size: 5rem; font-weight: 800; line-height: 1;
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }
        .score-waiting {
            font-size: 4rem; font-weight: 800; color: #fd7e14;
        }

        /* Detail Cards */
        .detail-card {
            background: #f8f9fa; border-radius: 20px; padding: 1.5rem;
            border: 1px solid #f0f0f0; transition: 0.3s; height: 100%;
        }
        .detail-card:hover { background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transform: translateY(-5px); }
        
        .detail-label { font-size: 0.9rem; text-transform: uppercase; color: #888; letter-spacing: 1px; font-weight: 600; }
        .detail-value { font-size: 2rem; font-weight: 700; color: #444; }

        /* Buttons */
        .btn-back {
            background: #f1f1f1; color: #555; border-radius: 50px;
            padding: 12px 30px; font-weight: 600; transition: 0.3s;
            text-decoration: none; display: inline-flex; align-items: center;
        }
        .btn-back:hover { background: #e2e2e2; color: #333; transform: translateY(-2px); }

        /* Footer */
        footer {
            background: white; border-top-left-radius: 30px; border-top-right-radius: 30px;
            box-shadow: 0 -4px 20px rgba(157, 78, 117, 0.08); margin-top: auto; width: 100%;
        }

        /* Animation */
        .fade-in-up { animation: fadeInUp 0.8s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
        .pop-in { animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes popIn { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
</head>

<body>
    {{-- Navbar --}}
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
            <div class="col-md-10 col-lg-8">
                
                <div class="result-card fade-in-up">
                    
                    <div class="icon-wrapper pop-in">
                        <i class="bi bi-trophy-fill"></i>
                    </div>

                    <h2 class="fw-bold mb-2">Ujian Selesai!</h2>
                    <p class="text-muted mb-4 fs-5">Anda telah menyelesaikan ujian: <br><strong class="text-dark">{{ $ujian->judul }}</strong></p>
                    
                    <hr class="my-4 opacity-10">

                    {{-- Logika Tampilan Skor --}}
                    @if ($hasil->total_skor !== null)
                        {{-- Skenario 1: Skor Final Tersedia --}}
                        <div class="mb-4">
                            <div class="score-big">{{ number_format($hasil->total_skor, 0) }}</div>
                            <p class="text-muted text-uppercase fw-bold letter-spacing-2">Skor Akhir Anda</p>
                        </div>
                    @else
                        {{-- Skenario 2: Menunggu Koreksi --}}
                        <div class="mb-4">
                            <div class="score-waiting">
                                {{ number_format($hasil->skor_pg, 0) }}<span class="fs-4 text-muted">+</span>
                            </div>
                            <p class="text-muted fw-bold">Skor Pilihan Ganda <span class="badge bg-warning text-dark ms-2">Esai Menunggu Koreksi</span></p>
                        </div>
                    @endif

                    {{-- Rincian Skor Grid --}}
                    <div class="row g-3 justify-content-center mb-5">
                        <div class="col-6 col-md-5">
                            <div class="detail-card">
                                <div class="detail-label mb-2">Pilihan Ganda</div>
                                <div class="detail-value text-primary">{{ number_format($hasil->skor_pg, 0) }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-5">
                            <div class="detail-card">
                                <div class="detail-label mb-2">Esai</div>
                                @if ($hasil->skor_esai !== null)
                                    <div class="detail-value text-success">{{ number_format($hasil->skor_esai, 0) }}</div>
                                @else
                                    <div class="detail-value text-warning fs-4 mt-1"><i class="bi bi-hourglass-split"></i></div>
                                    <small class="text-muted fw-bold">Pending</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($hasil->total_skor === null && $hasil->skor_esai === null)
                        <div class="alert alert-light border-warning text-warning-emphasis d-inline-block px-4 py-2 rounded-pill mb-4 small">
                            <i class="bi bi-info-circle-fill me-2"></i>Skor akhir akan diperbarui setelah dosen mengoreksi jawaban esai.
                        </div>
                        <br>
                    @endif
                    
                    <a href="{{ route('dashboard') }}" class="btn-back">
                        <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
                    </a>

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
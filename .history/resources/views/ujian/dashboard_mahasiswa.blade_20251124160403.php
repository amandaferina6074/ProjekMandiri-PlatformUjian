<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Dashboard Mahasiswa - Platform Ujian</title>
    
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

        /* Cards */
        .card {
            border: none; border-radius: 25px; box-shadow: var(--card-shadow);
            background: white; transition: all 0.3s ease; margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .card:hover { transform: translateY(-3px); box-shadow: var(--card-hover-shadow); }

        /* --- STYLE KHUSUS MAHASISWA --- */
        
        /* Token Box */
        .token-card {
            background: linear-gradient(135deg, #ffffff 0%, #fff0f5 100%);
            border: 2px solid white;
            text-align: center; padding: 3rem 2rem;
        }
        .token-icon-wrapper {
            width: 80px; height: 80px; border-radius: 50%;
            background: var(--app-primary); color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px rgba(157, 78, 117, 0.3);
        }
        
        .input-token {
            border: 3px solid var(--app-secondary);
            border-radius: 15px; padding: 15px;
            font-size: 1.5rem; letter-spacing: 5px; text-align: center;
            text-transform: uppercase; font-weight: 700;
            color: var(--app-primary); background: white;
            transition: all 0.3s;
        }
        .input-token:focus {
            border-color: var(--app-primary); box-shadow: 0 0 0 5px rgba(157, 78, 117, 0.1);
            outline: none;
        }
        .input-token::placeholder { letter-spacing: 1px; opacity: 0.4; font-weight: 400; }

        /* Table Styling */
        .table-custom { --bs-table-bg: transparent; }
        .table-custom thead th {
            background-color: var(--app-bg); color: var(--app-primary);
            border-bottom: 2px solid var(--app-secondary); font-weight: 600;
            text-transform: uppercase; font-size: 0.85rem; padding: 15px;
        }
        .table-custom tbody td {
            background: white; padding: 15px; vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }
        .table-custom tbody tr:first-child td:first-child { border-top-left-radius: 15px; }
        .table-custom tbody tr:first-child td:last-child { border-top-right-radius: 15px; }
        .table-custom tbody tr:last-child td:first-child { border-bottom-left-radius: 15px; }
        .table-custom tbody tr:last-child td:last-child { border-bottom-right-radius: 15px; }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            border: none; border-radius: 50px; padding: 12px 40px;
            font-weight: 600; box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
            transition: all 0.3s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(157, 78, 117, 0.4); }

        /* Footer */
        footer {
            background: white; border-top-left-radius: 30px; border-top-right-radius: 30px;
            box-shadow: 0 -4px 20px rgba(157, 78, 117, 0.08); margin-top: auto; width: 100%;
        }

        /* Animation */
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
        .pulse-animation { animation: pulse 2s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
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
        
        {{-- SECTION 1: INPUT TOKEN --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 col-lg-6">
                <div class="card token-card fade-in-up">
                    <div class="token-icon-wrapper pulse-animation">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark">Gabung Ujian</h3>
                    <p class="text-muted mb-4">Masukkan kode token ujian dari dosen Anda.</p>
                    
                    <form action="{{ route('ujian.search') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <input type="text" 
                                   name="token" 
                                   class="form-control input-token" 
                                   placeholder="X Y Z 1 2 3" 
                                   required
                                   autocomplete="off">
                        </div>
                        
                        <button class="btn btn-primary w-100 rounded-pill py-3 fs-5" type="submit">
                            CARI UJIAN SEKARANG <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>

                        @error('token')
                            <div class="alert alert-danger mt-3 rounded-3 border-0 bg-danger-subtle text-danger mb-0">
                                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ $message }}
                            </div>
                        @enderror
                    </form>
                </div>
            </div>
        </div>

        {{-- SECTION 2: RIWAYAT UJIAN --}}
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex align-items-center mb-4 fade-in-up" style="animation-delay: 0.2s;">
                    <h4 class="fw-bold text-secondary mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Ujian Anda</h4>
                </div>
                
                @if($riwayatUjian->isEmpty())
                    <div class="text-center py-5 fade-in-up" style="animation-delay: 0.3s;">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-inbox text-muted opacity-50" style="font-size: 2.5rem;"></i>
                        </div>
                        <p class="text-muted">Belum ada riwayat ujian. Masukkan token di atas untuk memulai.</p>
                    </div>
                @else
                    <div class="card shadow-sm border-0 bg-transparent fade-in-up" style="animation-delay: 0.3s;">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Mata Kuliah / Ujian</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatUjian as $hasil)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $hasil->ujian->judul }}</div>
                                            <small class="text-muted">{{ $hasil->ujian->deskripsi ?? '-' }}</small>
                                        </td>
                                        <td>
                                            {{ $hasil->started_at ? $hasil->started_at->format('d M Y, H:i') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if($hasil->finished_at)
                                                <span class="badge bg-success rounded-pill">Selesai</span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill">Belum Selesai</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($hasil->total_skor !== null)
                                                <span class="fw-bold fs-5 text-primary">{{ $hasil->total_skor }}</span>
                                            @elseif($hasil->skor_pg !== null)
                                                <small class="text-muted fst-italic">Menunggu<br>Koreksi</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            @if($hasil->finished_at)
                                                <a href="{{ route('pengerjaan.result', $hasil->ujian_id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    Lihat Hasil
                                                </a>
                                            @else
                                                <a href="{{ route('pengerjaan.show', $hasil->id) }}" class="btn btn-sm btn-warning fw-bold rounded-pill px-3">
                                                    Lanjutkan <i class="bi bi-play-fill"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </main>

    <footer class="text-center py-4">
        <p>&copy; {{ date('Y') }} Platform Ujian Online. All rights reserved.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
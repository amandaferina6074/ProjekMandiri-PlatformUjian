<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Detail Ujian: {{ $ujian->judul }}</title>
    
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

        /* Custom Components */
        .btn-back {
            color: var(--app-primary); text-decoration: none; font-weight: 600;
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 20px; background: white; border-radius: 50px;
            box-shadow: var(--card-shadow); transition: all 0.3s;
        }
        .btn-back:hover { transform: translateX(-5px); color: var(--app-primary-hover); }

        /* Token Card */
        .token-card {
            background: white; border: 2px dashed var(--app-primary);
            border-radius: 20px; padding: 2rem; text-align: center;
            position: relative; overflow: hidden;
        }
        .token-value {
            font-size: 3rem; font-weight: 800; letter-spacing: 5px;
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            margin: 10px 0;
        }

        /* Info Section */
        .info-card {
            background: white; border-radius: 25px; padding: 2rem;
            box-shadow: var(--card-shadow); margin-bottom: 2rem;
        }

        /* Soal List */
        .soal-item {
            background: white; border-radius: 15px; padding: 1.5rem;
            margin-bottom: 1rem; border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        .soal-item:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.05); transform: translateY(-2px); }
        
        .badge-type {
            background: var(--app-bg); color: var(--app-primary);
            padding: 5px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 600;
        }

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
            <a class="navbar-brand" href="{{ route('ujian.index') }}">
                <i class="bi bi-briefcase-fill me-2"></i>Dosen Panel
            </a>
            <div class="collapse navbar-collapse justify-content-end">
                <div class="d-flex align-items-center gap-3">
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
        
        {{-- Header Navigation & Info --}}
        <div class="row mb-4 fade-in-up">
            <div class="col-md-8">
                <a href="{{ route('ujian.index') }}" class="btn-back mb-3">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                </a>
                <h2 class="fw-bold text-dark mb-1">{{ $ujian->judul }}</h2>
                <p class="text-muted">{{ $ujian->deskripsi ?: 'Tidak ada deskripsi khusus.' }}</p>
            </div>
            <div class="col-md-4 text-end">
                <form action="{{ route('ujian.destroy', $ujian) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ujian ini beserta semua soalnya?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">
                        <i class="bi bi-trash me-2"></i> Hapus Ujian
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            {{-- KOLOM KIRI: Daftar Soal --}}
            <div class="col-lg-8 fade-in-up" style="animation-delay: 0.1s;">
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-secondary mb-0"><i class="bi bi-list-check me-2"></i>Daftar Soal</h5>
                    <a href="{{ route('soal.create', $ujian) }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Soal
                    </a>
                </div>

                @forelse ($ujian->soals as $key => $soal)
                    <div class="soal-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge-type">{{ $key + 1 }}. {{ ucfirst($soal->type) }}</span>
                            <div class="d-flex gap-2">
                                <a href="{{ route('soal.edit', $soal) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit Soal">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('soal.destroy', $soal) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus Soal">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="fs-5 mb-3 text-dark">{!! $soal->pertanyaan !!}</div>

                        @if ($soal->image_path)
                            <div class="mb-3">
                                <img src="{{ Storage::url($soal->image_path) }}" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            </div>
                        @endif

                        @if ($soal->type == 'pg')
                            <ul class="list-unstyled ps-2 border-start border-3 border-light">
                                @foreach ($soal->pilihanJawabans as $pilihan)
                                    <li class="mb-1 px-3 py-1 rounded {{ $pilihan->apakah_benar ? 'bg-success-subtle text-success fw-bold' : '' }}">
                                        <i class="bi {{ $pilihan->apakah_benar ? 'bi-check-circle-fill' : 'bi-circle' }} me-2"></i>
                                        {{ $pilihan->teks_pilihan }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                        <i class="bi bi-clipboard-x text-muted opacity-50" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Belum ada soal untuk ujian ini.</p>
                    </div>
                @endforelse

            </div>

            {{-- KOLOM KANAN: Token & Hasil --}}
            <div class="col-lg-4 fade-in-up" style="animation-delay: 0.2s;">
                
                {{-- Token Card --}}
                <div class="token-card mb-4">
                    <small class="text-muted fw-bold text-uppercase spacing-2">Token Akses Ujian</small>
                    <div class="token-value">{{ $ujian->token }}</div>
                    <p class="small text-muted mb-0">Berikan token ini kepada mahasiswa.</p>
                </div>

                {{-- Tabel Hasil --}}
                <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-trophy-fill me-2"></i>Hasil Mahasiswa</h5>
                
                <div class="bg-transparent">
                    @if($hasilUjiansSelesai->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-custom table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th class="text-center">Skor</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hasilUjiansSelesai as $hasil)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $hasil->user->name }}</div>
                                                <small class="text-muted">{{ $hasil->finished_at->format('d M, H:i') }}</small>
                                            </td>
                                            <td class="text-center">
                                                @if ($hasil->total_skor !== null)
                                                    <span class="badge bg-success rounded-pill">{{ number_format($hasil->total_skor, 0) }}</span>
                                                @else
                                                    <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('ujian.hasil', $hasil->id) }}" class="btn btn-sm btn-light text-info" title="Lihat">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                    @if ($hasil->skor_esai === null && $ujian->soals->contains('type', 'esai'))
                                                        <a href="{{ route('ujian.koreksi', $hasil->id) }}" class="btn btn-sm btn-light text-warning" title="Koreksi">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-white p-4 rounded-4 text-center shadow-sm">
                            <p class="text-muted mb-0 small">Belum ada mahasiswa yang menyelesaikan ujian.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </main>

    <footer class="text-center py-4">
        <p>&copy; {{ date('Y') }} Platform Ujian Online. All rights reserved.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        });
    </script>
</body>
</html>
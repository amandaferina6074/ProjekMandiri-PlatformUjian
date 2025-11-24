<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Daftar Ujian - Dosen Panel</title>
    
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {{-- Font modern --}}
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

        /* Footer */
        footer {
            background: white; border-top-left-radius: 30px; border-top-right-radius: 30px;
            box-shadow: 0 -4px 20px rgba(157, 78, 117, 0.08); margin-top: auto; width: 100%;
        }

        /* --- CUSTOM COMPONENT --- */
        .btn-back {
            color: var(--app-primary); text-decoration: none; font-weight: 600;
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 20px; background: white; border-radius: 50px;
            box-shadow: var(--card-shadow); transition: all 0.3s;
        }
        .btn-back:hover { transform: translateX(-5px); color: var(--app-primary-hover); }

        /* Ujian Card */
        .ujian-card {
            background: white; border-radius: 20px; padding: 25px;
            box-shadow: var(--card-shadow); transition: all 0.3s ease;
            border-left: 5px solid var(--app-primary); margin-bottom: 20px;
            position: relative; overflow: hidden;
        }
        .ujian-card:hover { transform: translateY(-5px); box-shadow: var(--card-hover-shadow); }
        
        .date-badge {
            background: var(--app-bg); color: var(--app-primary);
            padding: 5px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 600;
            display: inline-flex; align-items: center; gap: 5px;
        }

        .btn-action {
            width: 40px; height: 40px; border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.2s; border: none;
        }
        .btn-action:hover { transform: scale(1.1); }

        /* Add Button */
        .btn-add {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            color: white; border: none; border-radius: 50px; padding: 12px 30px;
            font-weight: 600; box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
            display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
        }
        .btn-add:hover { color: white; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(157, 78, 117, 0.4); }

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
        {{-- Header Page --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 fade-in-up">
            <div>
                <h2 class="fw-bold text-dark mb-1">Manajemen Ujian</h2>
                <p class="text-muted mb-0">Kelola daftar ujian, soal, dan jadwal untuk mahasiswa.</p>
            </div>
            <a href="{{ route('ujian.create') }}" class="btn-add mt-3 mt-md-0">
                <i class="bi bi-plus-lg"></i> Buat Ujian Baru
            </a>
        </div>

        {{-- Alert Notification --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 fade-in-up d-flex align-items-center mb-4">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div><strong>Berhasil!</strong> {{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                @forelse ($ujians as $ujian)
                    <div class="ujian-card fade-in-up" style="animation-delay: 0.1s;">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            
                            {{-- Info Ujian --}}
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h4 class="fw-bold text-dark mb-0">{{ $ujian->judul }}</h4>
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                                        {{ $ujian->soals_count }} Soal
                                    </span>
                                </div>
                                
                                <p class="text-muted small mb-3">{{ $ujian->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}</p>
                                
                                <div class="d-flex flex-wrap gap-3">
                                    @if($ujian->available_from && $ujian->available_to)
                                        <span class="date-badge">
                                            <i class="bi bi-calendar-check"></i> 
                                            Mulai: {{ $ujian->available_from->format('d M Y, H:i') }}
                                        </span>
                                        <span class="date-badge text-danger" style="background: #fff5f5; color: #dc3545;">
                                            <i class="bi bi-calendar-x"></i> 
                                            Selesai: {{ $ujian->available_to->format('d M Y, H:i') }}
                                        </span>
                                    @else
                                        <span class="date-badge text-secondary bg-light">
                                            <i class="bi bi-hourglass"></i> Jadwal belum diatur
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="d-flex gap-2">
                                <a href="{{ route('ujian.show', $ujian) }}" class="btn-action bg-info bg-opacity-10 text-info" title="Detail & Soal">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('ujian.edit', $ujian) }}" class="btn-action bg-warning bg-opacity-10 text-warning" title="Edit Ujian">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('ujian.destroy', $ujian) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ujian ini beserta seluruh soalnya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action bg-danger bg-opacity-10 text-danger" title="Hapus Ujian">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 fade-in-up">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                            <i class="bi bi-journal-plus text-muted opacity-50" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-muted">Belum ada ujian dibuat</h5>
                        <p class="text-muted small mb-4">Silakan buat ujian baru untuk memulai.</p>
                        <a href="{{ route('ujian.create') }}" class="btn btn-outline-primary rounded-pill px-4">
                            Buat Ujian Pertama
                        </a>
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if($ujians->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $ujians->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <footer class="text-center py-4">
        <p>&copy; {{ date('Y') }} Platform Ujian Online. All rights reserved.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Staggered Animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.ujian-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
            });
        });
    </script>
</body>
</html>
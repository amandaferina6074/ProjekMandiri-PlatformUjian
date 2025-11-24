<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Buat Ujian Baru</title>
    
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

        /* Card Form */
        .form-card {
            background: white; border-radius: 25px; padding: 2.5rem;
            box-shadow: var(--card-shadow); border-top: 5px solid var(--app-primary);
            position: relative; overflow: hidden;
        }

        /* Form Controls */
        .form-label { font-weight: 600; color: var(--app-text); margin-bottom: 8px; }
        
        .form-control {
            border-radius: 15px; padding: 12px 15px;
            border: 1px solid #eee; background-color: #fcfcfc;
            transition: all 0.3s;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(157, 78, 117, 0.1);
            border-color: var(--app-primary); background-color: white;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            border: none; border-radius: 50px; padding: 12px 30px;
            font-weight: 600; box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
            transition: all 0.3s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(157, 78, 117, 0.4); }

        .btn-secondary {
            background: #f8f9fa; color: var(--app-text); border: 1px solid #eee;
            border-radius: 50px; padding: 12px 25px; font-weight: 600;
        }
        .btn-secondary:hover { background: #e9ecef; color: var(--app-text); border-color: #ddd; }

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
                </div>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="text-center mb-4 fade-in-up">
                    <h2 class="fw-bold text-dark mb-1">Buat Ujian Baru</h2>
                    <p class="text-muted">Lengkapi detail ujian sebelum menambahkan soal.</p>
                </div>

                <div class="form-card fade-in-up" style="animation-delay: 0.1s;">
                    <form action="{{ route('ujian.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="judul" class="form-label"><i class="bi bi-type-h1 me-2 text-primary"></i>Judul Ujian</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" name="judul" value="{{ old('judul') }}" 
                                   placeholder="Contoh: Ujian Tengah Semester Pemrograman Web" required autofocus>
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="form-label"><i class="bi bi-card-text me-2 text-primary"></i>Deskripsi (Opsional)</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="3" 
                                      placeholder="Berikan instruksi singkat atau penjelasan ujian...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="p-3 bg-light rounded-4 mb-4 border border-dashed">
                            <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-clock-history me-2"></i>Pengaturan Waktu</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="durasi_menit" class="form-label small text-muted text-uppercase">Durasi (Menit)</label>
                                    <input type="number" class="form-control @error('durasi_menit') is-invalid @enderror" 
                                           id="durasi_menit" name="durasi_menit" value="{{ old('durasi_menit') }}" 
                                           placeholder="90" required min="1">
                                    @error('durasi_menit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="available_from" class="form-label small text-muted text-uppercase">Dibuka Mulai</label>
                                    <input type="datetime-local" class="form-control @error('available_from') is-invalid @enderror" 
                                           id="available_from" name="available_from" value="{{ old('available_from') }}" required>
                                    @error('available_from') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="available_to" class="form-label small text-muted text-uppercase">Ditutup Pada</label>
                                    <input type="datetime-local" class="form-control @error('available_to') is-invalid @enderror" 
                                           id="available_to" name="available_to" value="{{ old('available_to') }}" required>
                                    @error('available_to') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                            <a href="{{ route('ujian.index') }}" class="btn btn-secondary px-4 rounded-pill">
                                <i class="bi bi-arrow-left me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill">
                                Simpan & Lanjut <i class="bi bi-arrow-right ms-2"></i>
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
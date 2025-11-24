<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Tambah Soal - {{ $ujian->judul }}</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {{-- Alpine.js (Wajib untuk interaksi form) --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
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

        /* Card Form */
        .form-card {
            background: white; border-radius: 25px; padding: 2.5rem;
            box-shadow: var(--card-shadow); border-top: 5px solid var(--app-primary);
            position: relative; overflow: hidden; margin-bottom: 2rem;
        }

        /* Form Controls */
        .form-label { font-weight: 600; color: var(--app-text); margin-bottom: 8px; }
        
        .form-control, .form-select {
            border-radius: 15px; padding: 12px 15px;
            border: 1px solid #eee; background-color: #fcfcfc;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(157, 78, 117, 0.1);
            border-color: var(--app-primary); background-color: white;
        }

        /* Custom File Input */
        input[type="file"]::file-selector-button {
            background: var(--app-bg); color: var(--app-primary); border: none;
            padding: 8px 15px; border-radius: 10px; margin-right: 10px; font-weight: 600;
            cursor: pointer; transition: 0.3s;
        }
        input[type="file"]::file-selector-button:hover { background: var(--app-secondary); }

        /* Option Cards */
        .option-group .input-group-text {
            background: white; border: 1px solid #eee; border-right: none;
            border-top-left-radius: 15px; border-bottom-left-radius: 15px;
            padding-left: 15px;
        }
        .option-group .form-control {
            border-left: none; border-top-left-radius: 0; border-bottom-left-radius: 0;
        }
        .form-check-input:checked {
            background-color: var(--app-primary); border-color: var(--app-primary);
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
            <div class="col-lg-8" x-data="{ type: '{{ old('type', 'pg') }}' }">
                
                {{-- Header --}}
                <div class="text-center mb-4 fade-in-up">
                    <h2 class="fw-bold text-dark mb-1">Formulir Soal Baru</h2>
                    <p class="text-muted">Menambahkan soal untuk: <strong>{{ $ujian->judul }}</strong></p>
                </div>

                <div class="form-card fade-in-up" style="animation-delay: 0.1s;">
                    <form action="{{ route('soal.store', $ujian) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Tipe Soal --}}
                        <div class="mb-4">
                            <label for="type" class="form-label">
                                <i class="bi bi-sliders me-2 text-primary"></i>Tipe Soal
                            </label>
                            <select class="form-select" id="type" name="type" x-model="type">
                                <option value="pg">Pilihan Ganda</option>
                                <option value="esai">Esai</option>
                            </select>
                        </div>

                        {{-- Pertanyaan --}}
                        <div class="mb-4">
                            <label for="pertanyaan" class="form-label">
                                <i class="bi bi-chat-right-text me-2 text-primary"></i>Pertanyaan
                            </label>
                            <textarea class="form-control @error('pertanyaan') is-invalid @enderror" 
                                      id="pertanyaan" name="pertanyaan" rows="4" 
                                      placeholder="Tuliskan pertanyaan di sini..." required>{{ old('pertanyaan') }}</textarea>
                            @error('pertanyaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Upload Gambar --}}
                        <div class="mb-4">
                            <label for="gambar_soal" class="form-label">
                                <i class="bi bi-image me-2 text-primary"></i>Gambar Pendukung (Opsional)
                            </label>
                            <input class="form-control @error('gambar_soal') is-invalid @enderror" type="file" id="gambar_soal" name="gambar_soal">
                            <div class="form-text text-muted small">Format: JPG, PNG, JPEG. Maksimal 2MB.</div>
                            @error('gambar_soal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4 border-secondary opacity-25">

                        {{-- Section Pilihan Ganda (Alpine x-show) --}}
                        <div x-show="type === 'pg'" x-transition.duration.500ms>
                            <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-list-ul me-2"></i>Pilihan Jawaban</h5>
                            <div class="alert alert-info border-0 bg-info-subtle text-info-emphasis small mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i>Isi teks pilihan dan <strong>klik tombol bulat</strong> di sebelah kiri untuk menandai jawaban yang benar.
                            </div>
                            
                            @for ($i = 0; $i < 4; $i++)
                            <div class="input-group option-group mb-3">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" 
                                           type="radio" 
                                           value="{{ $i }}" 
                                           name="jawaban_benar" 
                                           {{ old('jawaban_benar') == $i ? 'checked' : '' }}
                                           title="Tandai sebagai jawaban benar"
                                           x-bind:required="type === 'pg'"
                                           x-bind:disabled="type !== 'pg'">
                                </div>
                                <input type="text" 
                                       class="form-control @error('pilihan.'.$i) is-invalid @enderror" 
                                       name="pilihan[{{ $i }}]" 
                                       placeholder="Pilihan Jawaban {{ chr(65 + $i) }}" 
                                       value="{{ old('pilihan.'.$i) }}"
                                       x-bind:required="type === 'pg'"
                                       x-bind:disabled="type !== 'pg'">
                            </div>
                            @endfor
                            
                            @error('pilihan.*') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
                            @error('jawaban_benar') <div class="text-danger fw-bold small mb-2"><i class="bi bi-exclamation-circle me-1"></i>Wajib memilih satu jawaban benar!</div> @enderror
                        </div>

                        {{-- Section Esai (Alpine x-show) --}}
                        <div x-show="type === 'esai'" x-transition.duration.500ms style="display: none;">
                            <div class="alert alert-warning border-0 bg-warning-subtle text-warning-emphasis small">
                                <i class="bi bi-pencil-fill me-2"></i>Untuk soal Esai, mahasiswa akan mengetik jawaban mereka secara bebas. Dosen perlu mengoreksi manual.
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                            <a href="{{ route('ujian.show', $ujian) }}" class="btn btn-secondary px-4 rounded-pill">
                                <i class="bi bi-arrow-left me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill">
                                Simpan Soal <i class="bi bi-check-lg ms-2"></i>
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
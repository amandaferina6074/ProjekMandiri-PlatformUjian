<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Koreksi Esai - {{ $hasilUjian->user->name }}</title>
    
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

        /* Info Card */
        .info-card {
            background: white; border-radius: 20px; padding: 1.5rem 2rem;
            box-shadow: var(--card-shadow); margin-bottom: 2rem;
            border-left: 5px solid var(--app-primary);
        }

        /* Question Card */
        .question-card {
            background: white; border-radius: 20px; padding: 2rem;
            box-shadow: var(--card-shadow); margin-bottom: 1.5rem;
            transition: all 0.3s;
        }
        .question-card:hover { box-shadow: var(--card-hover-shadow); }

        .question-badge {
            background: var(--app-bg); color: var(--app-primary);
            padding: 5px 15px; border-radius: 50px; font-weight: 600; font-size: 0.85rem;
            display: inline-block; margin-bottom: 15px;
        }

        /* Answer Box */
        .answer-box {
            background-color: #f8f9fa; border: 1px solid #e9ecef;
            border-radius: 15px; padding: 1.5rem; margin-top: 1.5rem;
            border-left: 4px solid var(--app-secondary);
        }
        .answer-title {
            font-size: 0.85rem; text-transform: uppercase; color: #888;
            font-weight: 700; margin-bottom: 10px; letter-spacing: 1px;
        }

        /* Score Input */
        .score-input-group {
            background: var(--app-bg-alt); padding: 15px; border-radius: 15px;
            display: flex; align-items: center; gap: 15px; margin-top: 1.5rem;
            border: 1px dashed var(--app-primary);
        }
        .form-control-score {
            border-radius: 10px; border: 2px solid #ddd; padding: 8px 12px;
            width: 100px; text-align: center; font-weight: bold; color: var(--app-primary);
        }
        .form-control-score:focus {
            border-color: var(--app-primary); box-shadow: 0 0 0 3px rgba(157, 78, 117, 0.1);
        }

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
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                {{-- Header Navigation --}}
                <div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
                    <a href="{{ route('ujian.show', $hasilUjian->ujian_id) }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali ke Detail
                    </a>
                    <h2 class="fw-bold text-dark mb-0">Koreksi Jawaban</h2>
                </div>

                {{-- Info Mahasiswa --}}
                <div class="info-card fade-in-up" style="animation-delay: 0.1s;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold mb-1">{{ $hasilUjian->user->name }}</h4>
                            <p class="text-muted mb-0">{{ $hasilUjian->ujian->judul }}</p>
                        </div>
                        <div class="text-end">
                            <small class="text-muted text-uppercase fw-bold">Skor PG Saat Ini</small>
                            <div class="fs-2 fw-bold" style="color: var(--app-primary);">
                                {{ $hasilUjian->skor_pg ?? 0 }}
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('ujian.simpanKoreksi', $hasilUjian->id) }}" method="POST">
                    @csrf

                    @foreach($jawabanEsai as $index => $jawaban)
                        <div class="question-card fade-in-up" style="animation-delay: {{ ($index * 0.1) + 0.2 }}s">
                            
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="question-badge">Soal Esai No. {{ $index + 1 }}</span>
                            </div>

                            {{-- Pertanyaan --}}
                            <div class="fs-5 mb-3 text-dark">
                                {!! $jawaban->soal->pertanyaan !!}
                            </div>

                            @if($jawaban->soal->image_path)
                                <img src="{{ Storage::url($jawaban->soal->image_path) }}" class="img-fluid rounded mb-3" style="max-height: 250px;">
                            @endif

                            {{-- Jawaban Mahasiswa --}}
                            <div class="answer-box">
                                <div class="answer-title"><i class="bi bi-pencil-fill me-2"></i>Jawaban Mahasiswa</div>
                                <p class="mb-0 text-dark whitespace-pre-line">
                                    {{ $jawaban->jawaban_esai ?? '[Mahasiswa tidak menjawab]' }}
                                </p>
                            </div>

                            {{-- Input Skor --}}
                            <div class="score-input-group">
                                <label class="fw-bold text-dark mb-0">
                                    <i class="bi bi-award-fill me-2 text-warning"></i>Berikan Nilai (0-100):
                                </label>
                                <input type="number" name="skor_esai[]" 
                                       class="form-control form-control-score" 
                                       placeholder="0" min="0" max="100" required>
                            </div>

                        </div>
                    @endforeach

                    <div class="d-flex justify-content-end mt-4 mb-5 fade-in-up" style="animation-delay: 0.5s;">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fs-5 shadow-lg">
                            <i class="bi bi-check-circle-fill me-2"></i>Simpan Nilai & Selesai
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </main>

    <footer class="text-center py-4">
        <p>&copy; {{ date('Y') }} Platform Ujian Online. All rights reserved.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
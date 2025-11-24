<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Hasil Ujian - {{ $hasilUjian->ujian->judul }}</title>
    
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

        
        /* Header Score Card */
        .score-card {
            background: linear-gradient(135deg, var(--app-primary) 0%, #8e24aa 100%);
            color: white; padding: 2rem; border-radius: 25px;
            position: relative; overflow: hidden;
        }
        .score-card::after {
            content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px;
            background: rgba(255,255,255,0.1); border-radius: 50%;
        }
        
        .score-value {
            font-size: 3.5rem; font-weight: 800; line-height: 1;
            text-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .score-label { font-size: 0.9rem; text-transform: uppercase; opacity: 0.9; letter-spacing: 1px; }

        /* Detail Skor Box */
        .detail-score-box {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border-radius: 15px; padding: 10px 20px;
            display: flex; gap: 20px; margin-top: 20px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        /* Question Card */
        .question-badge {
            background: var(--app-bg); color: var(--app-primary);
            padding: 5px 15px; border-radius: 50px; font-weight: 600; font-size: 0.85rem;
            display: inline-block; margin-bottom: 10px;
        }
        
        .question-image {
            max-height: 250px; border-radius: 15px; margin-top: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); border: 4px solid #fff;
        }

        /* Answer Box Styles */
        .answer-box {
            margin-top: 1.5rem; padding: 1.5rem; border-radius: 15px;
            border-left: 5px solid #ddd; background-color: #f9f9f9;
        }
        
        /* Status Colors */
        .status-correct {
            border-left-color: #198754; background-color: #d1e7dd; color: #0f5132;
        }
        .status-wrong {
            border-left-color: #dc3545; background-color: #f8d7da; color: #842029;
        }
        .status-neutral {
            border-left-color: #ffc107; background-color: #fff3cd; color: #664d03;
        }

        /* Button Back */
        .btn-back {
            color: var(--app-primary); text-decoration: none; font-weight: 600;
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 20px; background: white; border-radius: 50px;
            box-shadow: var(--card-shadow); transition: all 0.3s;
        }
        .btn-back:hover { transform: translateX(-5px); color: var(--app-primary-hover); }

        /* Footer */
        footer {
            background: white; border-top-left-radius: 30px; border-top-right-radius: 30px;
            box-shadow: 0 -4px 20px rgba(157, 78, 117, 0.08); margin-top: auto; width: 100%;
        }

        /* Animation */
        .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body>
    {{-- Navbar --}}
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
            <div class="col-lg-10">
                
                {{-- Header Navigation --}}
                <div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
                    <a href="{{ route('dashboard') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div>

                {{-- 1. Score Card Header --}}
                <div class="card score-card fade-in-up" style="animation-delay: 0.1s">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-1">{{ $hasilUjian->ujian->judul }}</h2>
                            <p class="mb-0 opacity-75"><i class="bi bi-person me-2"></i>{{ $hasilUjian->user->name }}</p>
                            
                            {{-- Detail Skor --}}
                            <div class="detail-score-box">
                                <div>
                                    <span class="d-block small opacity-75">Skor PG</span>
                                    <span class="fw-bold fs-5">{{ $hasilUjian->skor_pg }}</span>
                                </div>
                                <div class="border-start border-light opacity-50"></div>
                                <div>
                                    <span class="d-block small opacity-75">Skor Esai</span>
                                    <span class="fw-bold fs-5">{{ $hasilUjian->skor_esai ?? 'Menunggu' }}</span>
                                </div>
                            </div>

                            {{-- Tombol Koreksi (Hanya untuk Dosen jika belum dinilai) --}}
                            @if($hasilUjian->skor_esai === null && $hasilUjian->ujian->soals->where('type', 'esai')->count() > 0)
                                <div class="mt-4">
                                    <a href="{{ route('ujian.koreksi', $hasilUjian->id) }}" class="btn btn-light text-primary rounded-pill fw-bold px-4 shadow-sm">
                                        <i class="bi bi-pencil-square me-2"></i>Lakukan Koreksi Esai
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4 text-center mt-4 mt-md-0">
                            <div class="score-label">TOTAL SKOR</div>
                            @if($hasilUjian->total_skor !== null)
                                <div class="score-value">{{ $hasilUjian->total_skor }}</div>
                            @else
                                <div class="fs-3 fw-bold text-warning mt-2">
                                    <i class="bi bi-hourglass-split"></i> Pending
                                </div>
                                <small class="text-white-50">Menunggu koreksi dosen</small>
                            @endif
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mb-4 text-secondary fade-in-up" style="animation-delay: 0.2s">
                    <i class="bi bi-list-check me-2"></i>Review Jawaban
                </h5>

                {{-- 2. Daftar Soal Loop --}}
                @foreach($hasilUjian->ujian->soals as $index => $soal)
                    <div class="card fade-in-up" style="animation-delay: {{ ($index * 0.1) + 0.3 }}s">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="question-badge">
                                    Soal No. {{ $index + 1 }} • {{ ucfirst($soal->type) }}
                                </span>
                            </div>

                            {{-- Pertanyaan --}}
                            <div class="fs-5 text-dark mb-3">{!! $soal->pertanyaan !!}</div>

                            {{-- Gambar Soal --}}
                            @if($soal->image_path)
                                <img src="{{ Storage::url($soal->image_path) }}" class="question-image img-fluid">
                            @endif

                            {{-- Logika Jawaban --}}
                            @php 
                                $jawabanUser = $jawabanMap->get($soal->id); 
                                $statusClass = 'status-neutral'; // Default (Esai/Tidak dijawab)
                                $statusIcon = 'bi-dash-circle';
                                $statusText = 'Tidak Menjawab';

                                if ($soal->type == 'pilihan_ganda') {
                                    if ($jawabanUser && $jawabanUser->pilihan_jawaban_id) {
                                        $pilihanUser = $soal->pilihanJawabans->find($jawabanUser->pilihan_jawaban_id);
                                        $isCorrect = $pilihanUser && $pilihanUser->apakah_benar;
                                        
                                        $statusClass = $isCorrect ? 'status-correct' : 'status-wrong';
                                        $statusIcon = $isCorrect ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
                                        $statusText = $isCorrect ? 'JAWABAN BENAR' : 'JAWABAN SALAH';
                                        $answerText = $pilihanUser->teks_pilihan ?? '-';
                                    } else {
                                        $statusClass = 'status-wrong';
                                        $statusIcon = 'bi-x-circle';
                                        $statusText = 'TIDAK DIJAWAB';
                                        $answerText = '-';
                                    }
                                } elseif ($soal->type == 'esai') {
                                    $statusClass = 'status-neutral';
                                    $statusIcon = 'bi-pencil-fill';
                                    $statusText = 'JAWABAN ESAI';
                                    $answerText = $jawabanUser->jawaban_esai ?? '-';
                                }
                            @endphp

                            {{-- Kotak Jawaban --}}
                            <div class="answer-box {{ $statusClass }}">
                                <div class="d-flex align-items-center mb-2 fw-bold small opacity-75">
                                    <i class="bi {{ $statusIcon }} me-2 fs-6"></i> {{ $statusText }}
                                </div>
                                <div class="fs-6">
                                    @if($soal->type == 'esai')
                                        <p class="mb-0 whitespace-pre-line">{{ $answerText }}</p>
                                    @else
                                        {{ $answerText }}
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="text-center py-4">
        <p>&copy; {{ date('Y') }} Platform Ujian Online. All rights reserved.</p>
    </footer>
    
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Platform Ujian</title>
    
    {{-- 1. Assets (Bootstrap & Fonts) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --app-primary: #9D4E75;
            --app-primary-hover: #7a3b5a; 
            --app-secondary: #E8B4D0;
            --app-bg: #FFF5F9;
            --app-bg-alt: #FFEEF7;
            --app-text: #444444;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--app-bg) 0%, var(--app-bg-alt) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--app-text);
        }

        /* Card Container */
        .otp-card {
            background: white;
            width: 100%;
            max-width: 450px;
            padding: 40px 30px;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(157, 78, 117, 0.15);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.5);
        }

        /* Dekorasi Atas */
        .otp-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 5px;
            background: linear-gradient(90deg, var(--app-primary), var(--app-secondary));
        }

        /* Icon Kunci */
        .icon-wrapper {
            width: 80px; height: 80px;
            background-color: var(--app-bg);
            color: var(--app-primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(157, 78, 117, 0.1);
        }

        h2 { font-weight: 700; color: #333; margin-bottom: 10px; }
        p.desc { font-size: 14px; color: #666; margin-bottom: 30px; line-height: 1.6; }

        /* Input OTP Custom */
        .form-control-otp {
            border: 2px solid #eee;
            border-radius: 15px;
            padding: 15px;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 12px; /* Jarak antar angka */
            text-align: center;
            color: var(--app-primary);
            background-color: #fafafa;
            transition: all 0.3s;
        }
        
        .form-control-otp:focus {
            background-color: white;
            border-color: var(--app-primary);
            box-shadow: 0 0 0 5px rgba(157, 78, 117, 0.15);
            outline: none;
        }

        .form-control-otp::placeholder {
            color: #ddd; letter-spacing: 5px; font-weight: 400; font-size: 1.5rem;
        }

        /* Tombol */
        .btn-verify {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            border: none; border-radius: 50px;
            padding: 12px 30px; width: 100%;
            color: white; font-weight: 600; font-size: 1rem;
            margin-top: 25px;
            box-shadow: 0 5px 15px rgba(157, 78, 117, 0.3);
            transition: all 0.3s;
        }
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(157, 78, 117, 0.4);
        }

        .back-link {
            display: inline-block; margin-top: 20px;
            color: #888; text-decoration: none; font-size: 14px;
            transition: 0.3s;
        }
        .back-link:hover { color: var(--app-primary); }

        /* Alert Error */
        .alert-custom {
            background-color: #fee2e2; color: #dc2626;
            border-radius: 15px; font-size: 13px; text-align: left;
            border: none; margin-bottom: 20px;
        }
        
        /* Highlight untuk lokasi Log */
        .log-path {
            background-color: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            color: #c7254e;
        }
    </style>
</head>
<body>

    <div class="otp-card">
        {{-- Icon --}}
        <div class="icon-wrapper">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        {{-- Judul --}}
        <h2>Verifikasi Masuk</h2>
        <div class="desc">
            {{-- PERUBAHAN TEKS 1: Tidak menyebut email --}}
            Demi keamanan akun, silakan masukkan <strong>6 digit kode OTP</strong> yang telah dihasilkan oleh sistem .
        </div>

        {{-- Notifikasi Error --}}
        @if ($errors->any())
            <div class="alert alert-custom p-3 fade show">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Gagal Verifikasi:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('status'))
             <div class="alert alert-success p-3 rounded-4 mb-4 small text-start">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
            </div>
        @endif

        {{-- Form OTP --}}
        <form method="POST" action="{{ route('otp.store') }}">
            @csrf

            <div class="mb-3">
                <label for="otp" class="form-label fw-bold small text-uppercase text-muted" style="letter-spacing: 1px;">Kode OTP</label>
                <input id="otp" 
                       class="form-control form-control-otp" 
                       type="text" 
                       name="otp" 
                       required 
                       autofocus 
                       placeholder="------"
                       maxlength="6"
                       inputmode="numeric" 
                       autocomplete="one-time-code">
                
                {{-- PERUBAHAN TEKS 2: Petunjuk lokasi Log --}}
                <div class="form-text mt-2 small text-muted">
                    <i class="bi bi-info-circle me-1"></i>Kode tercatat di file: <span class="log-path">storage/logs/laravel.log</span>
                </div>
            </div>

            <button type="submit" class="btn btn-verify">
                Verifikasi & Masuk <i class="bi bi-arrow-right-circle ms-2"></i>
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Login
        </a>
    </div>

    {{-- Script untuk auto-focus dan input number only --}}
    <script>
        document.getElementById('otp').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
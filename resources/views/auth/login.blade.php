<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Platform Ujian</title>
    
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {{-- Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --app-primary: #9D4E75;
            --app-primary-hover: #7a3b5a; 
            --app-secondary: #E8B4D0;
            --app-bg: #FFF5F9;
            --app-bg-alt: #FFEEF7;
        }

        body { 
            background: linear-gradient(135deg, var(--app-bg) 0%, var(--app-bg-alt) 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }

        /* Background Blobs */
        body::before, body::after {
            content: ''; position: absolute; border-radius: 50%; z-index: -1;
        }
        body::before {
            top: -100px; right: -100px; width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(157, 78, 117, 0.1) 0%, transparent 70%);
        }
        body::after {
            bottom: -100px; left: -100px; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(157, 78, 117, 0.08) 0%, transparent 70%);
        }

        .auth-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 10px 40px rgba(157, 78, 117, 0.1);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
            border-top: 5px solid var(--app-primary);
        }

        .logo-circle {
            width: 70px; height: 70px; border-radius: 50%;
            background: var(--app-bg); color: var(--app-primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 1.5rem;
        }

        .form-control {
            border-radius: 50px; padding: 12px 20px;
            border: 1px solid #eee; background-color: #fcfcfc;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(157, 78, 117, 0.1);
            border-color: var(--app-primary);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            border: none; border-radius: 50px; padding: 12px;
            font-weight: 600; width: 100%; letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(157, 78, 117, 0.4);
        }

        .auth-link { color: var(--app-primary); text-decoration: none; font-weight: 500; }
        .auth-link:hover { text-decoration: underline; }
        
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="auth-card fade-in-up">
        <div class="text-center mb-4">
            <div class="logo-circle">
                <i class="bi bi-person-lock"></i>
            </div>
            <h3 class="fw-bold text-dark">Selamat Datang</h3>
            <p class="text-muted small">Silakan login untuk melanjutkan ujian.</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success small rounded-3 mb-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback small ps-3">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Password" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback small ps-3">{{ $message }}</div>
                @enderror
            </div>

            <!-- ingat saya & lup password -->
            <div class="d-flex justify-content-between align-items-center mb-4 small">
                <div class="form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label for="remember_me" class="form-check-label text-muted">Ingat Saya</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link text-muted">Lupa Password?</a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary mb-3">
                MASUK SEKARANG
            </button>

            <div class="text-center small text-muted">
                Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar disini</a>
            </div>
        </form>
    </div>

</body>
</html>
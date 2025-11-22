<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Platform Ujian</title>
    
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    {{-- Alpine.js untuk Interaksi Role --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            padding: 20px 0;
        }

        .auth-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 10px 40px rgba(157, 78, 117, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 550px; /* Lebih lebar sedikit dari login */
            border-top: 5px solid var(--app-primary);
        }

        .form-control, .form-select {
            border-radius: 15px; padding: 12px 20px;
            border: 1px solid #eee; background-color: #fcfcfc;
            margin-bottom: 15px;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(157, 78, 117, 0.1);
            border-color: var(--app-primary);
        }

        /* Styling Radio Button Role */
        .role-selector input[type="radio"] { display: none; }
        .role-selector label {
            border: 2px solid #eee;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9rem;
            font-weight: 500;
            color: #666;
            display: flex; align-items: center; gap: 8px;
        }
        .role-selector input[type="radio"]:checked + label {
            border-color: var(--app-primary);
            background-color: var(--app-bg);
            color: var(--app-primary);
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
        
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="auth-card fade-in-up">
        <div class="text-center mb-4">
            <h3 class="fw-bold" style="color: var(--app-primary)">Buat Akun Baru</h3>
            <p class="text-muted small">Bergabunglah untuk memulai ujian online.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" x-data="{ role: 'mahasiswa' }">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
                @error('name') <div class="text-danger small ms-2 mb-2">{{ $message }}</div> @enderror
            </div>

            <!-- Pilihan Role  -->
            <div class="mb-3">
                <label class="fw-bold small text-muted mb-2 d-block">Daftar Sebagai:</label>
                <div class="d-flex gap-2 role-selector flex-wrap">
                    
                    <div>
                        <input type="radio" id="role_mahasiswa" name="role" value="mahasiswa" x-model="role">
                        <label for="role_mahasiswa"><i class="bi bi-mortarboard-fill"></i> Mahasiswa</label>
                    </div>

                    <div>
                        <input type="radio" id="role_dosen" name="role" value="dosen" x-model="role">
                        <label for="role_dosen"><i class="bi bi-briefcase-fill"></i> Dosen</label>
                    </div>

                    <div>
                        <input type="radio" id="role_admin" name="role" value="admin" x-model="role">
                        <label for="role_admin" class="text-danger border-danger-subtle"><i class="bi bi-shield-lock-fill"></i> Admin</label>
                    </div>

                </div>
                @error('role') <div class="text-danger small ms-2 mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- Input Kode Dosen -->
            <div x-show="role === 'dosen'" x-transition style="display: none;">
                <input type="text" name="kode_dosen" class="form-control border-info bg-info-subtle" 
                       placeholder="Masukkan Kode Validasi Dosen" value="{{ old('kode_dosen') }}">
                @error('kode_dosen') <div class="text-danger small ms-2 mb-2">{{ $message }}</div> @enderror
            </div>

            <!-- Input Kode Admin  -->
            <div x-show="role === 'admin'" x-transition style="display: none;">
                <input type="password" name="kode_admin" class="form-control border-danger bg-danger-subtle" 
                       placeholder="Masukkan Kode Rahasia Admin" value="{{ old('kode_admin') }}">
                @error('kode_admin') <div class="text-danger small ms-2 mb-2">{{ $message }}</div> @enderror
            </div>

            <!-- Email -->
            <div>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       placeholder="Alamat Email" value="{{ old('email') }}" required>
                @error('email') <div class="text-danger small ms-2 mb-2">{{ $message }}</div> @enderror
            </div>

            <!-- Password -->
            <div>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Password Baru" required autocomplete="new-password">
                @error('password') <div class="text-danger small ms-2 mb-2">{{ $message }}</div> @enderror
            </div>

            <!-- konfirmasi Password -->
            <div>
                <input type="password" name="password_confirmation" class="form-control" 
                       placeholder="Konfirmasi Password" required>
            </div>

            <button type="submit" class="btn btn-primary mt-2 mb-3">
                DAFTAR SEKARANG
            </button>

            <div class="text-center small text-muted">
                Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Login disini</a>
            </div>
        </form>
    </div>

</body>
</html>
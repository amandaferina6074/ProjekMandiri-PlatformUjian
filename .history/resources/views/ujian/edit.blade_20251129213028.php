<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Ujian - Dosen Panel</title>

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
            --card-shadow: 0 8px 30px rgba(157, 78, 117, 0.1);
            --card-hover-shadow: 0 12px 40px rgba(157, 78, 117, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: linear-gradient(135deg, var(--app-bg) 0%, var(--app-bg-alt) 100%);
            font-family: 'Poppins', sans-serif;
            color: var(--app-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

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
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
        }

        footer {
            background: white;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            box-shadow: 0 -4px 20px rgba(157, 78, 117, 0.08);
            margin-top: auto;
            width: 100%;
        }

        .btn-back {
            color: var(--app-primary);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            background: white;
            border-radius: 50px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s;
        }

        .btn-back:hover {
            transform: translateX(-5px);
            color: var(--app-primary-hover);
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            border-top: 5px solid var(--app-primary);
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 10px 15px;
            border-color: var(--app-secondary);
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--app-primary);
            box-shadow: 0 0 0 0.25rem rgba(157, 78, 117, 0.25);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--app-primary) 0%, #C05D8A 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(157, 78, 117, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(157, 78, 117, 0.4);
            background: linear-gradient(135deg, var(--app-primary-hover) 0%, #A04D70 100%);
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
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
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 fade-in-up">
            <h2 class="fw-bold text-dark mb-1">Edit Ujian: {{ $ujian->judul }}</h2>
            <a href="{{ route('ujian.show', $ujian) }}" class="btn-back mt-3 mt-md-0">
                <i class="bi bi-arrow-left"></i> Kembali ke Detail Ujian
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in-up" style="animation-delay: 0.1s;">
                <div class="form-card">

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 d-flex align-items-center mb-4">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Terjadi Kesalahan!</h6>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('ujian.update', $ujian) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="judul" class="form-label fw-bold">Judul Ujian</label>
                            <input type="text" name="judul" class="form-control" id="judul" value="{{ old('judul', $ujian->judul) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3">{{ old('deskripsi', $ujian->deskripsi) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="durasi_menit" class="form-label fw-bold">Durasi (menit)</label>
                            <input type="number" name="durasi_menit" class="form-control" id="durasi_menit" value="{{ old('durasi_menit', $ujian->durasi_menit) }}" required min="10">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="available_from" class="form-label fw-bold">Mulai Tersedia</label>
                                <input type="datetime-local" name="available_from" class="form-control" id="available_from" value="{{ old('available_from', $ujian->available_from->format('Y-m-d\TH:i')) }}" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="available_to" class="form-label fw-bold">Berakhir</label>
                                <input type="datetime-local" name="available_to" class="form-control" id="available_to" value="{{ old('available_to', $ujian->available_to->format('Y-m-d\TH:i')) }}" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn-primary-custom">
                                <i class="bi bi-save-fill"></i> Simpan Perubahan
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

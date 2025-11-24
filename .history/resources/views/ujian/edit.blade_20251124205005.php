<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ujian</title>
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- CSS custom jika ada --}}
    <link rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('ujian.index') }}">Platform Ujian</a>
        </div>
    </nav>

    <main class="container">
        <h2>Edit Ujian</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ujian.update', $ujian) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="judul" class="form-label">Judul Ujian</label>
                <input type="text" name="judul" class="form-control" id="judul" value="{{ old('judul', $ujian->judul) }}" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" id="deskripsi">{{ old('deskripsi', $ujian->deskripsi) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="durasi_menit" class="form-label">Durasi (menit)</label>
                <input type="number" name="durasi_menit" class="form-control" id="durasi_menit" value="{{ old('durasi_menit', $ujian->durasi_menit) }}" required>
            </div>

            <div class="mb-3">
                <label for="available_from" class="form-label">Mulai Tersedia</label>
                <input type="datetime-local" name="available_from" class="form-control" id="available_from" value="{{ old('available_from', $ujian->available_from->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="mb-3">
                <label for="available_to" class="form-label">Berakhir</label>
                <input type="datetime-local" name="available_to" class="form-control" id="available_to" value="{{ old('available_to', $ujian->available_to->format('Y-m-d\TH:i')) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('ujian.show', $ujian) }}" class="btn btn-secondary">Batal</a>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

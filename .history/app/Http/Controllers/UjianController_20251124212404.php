<?php  

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Soal;
use App\Models\HasilUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UjianController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'dosen') {
            $ujians = Ujian::where('user_id', $user->id)
                           ->withCount('soals')
                           ->latest()
                           ->paginate(10);
            return view('ujian.index_dosen', compact('ujians'));
        } else {
            $riwayatUjian = HasilUjian::with('ujian')
                                ->where('user_id', $user->id)
                                ->latest()
                                ->get();
            return view('ujian.dashboard_mahasiswa', compact('riwayatUjian'));
        }
    }

    public function searchByToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $ujian = Ujian::where('token', strtoupper($request->token))->first();

        if (!$ujian) {
            return back()->withErrors(['token' => 'Token tidak ditemukan atau ujian tidak ada.']);
        }

        $now = now();

        if ($ujian->available_from && $now->lt($ujian->available_from)) {
            return back()->withErrors(['token' => 'Ujian ini belum dibuka.']);
        }

        if ($ujian->available_to && $now->gt($ujian->available_to)) {
            return back()->withErrors(['token' => 'Ujian ini sudah ditutup.']);
        }

        return redirect()->route('pengerjaan.start', $ujian);
    }

    // FORM BUAT UJIAN
    public function create()
    {
        return view('ujian.create');
    }

    // SIMPAN UJIAN BARU
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi_menit' => 'required|integer|min:1',
            'available_from' => 'required|date',
            'available_to' => 'required|date|after:available_from',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['token'] = strtoupper(Str::random(6));

        $ujian = Ujian::create($data);

        return redirect()->route('ujian.show', $ujian)
            ->with('status', 'Ujian berhasil dibuat! Token Akses: ' . $data['token']);
    }

    // DETAIL UJIAN
    public function show(Ujian $ujian)
    {
        if (Auth::user()->role === 'dosen' && $ujian->user_id !== Auth::id()) {
            abort(403);
        }

        $ujian->load('soals.pilihanJawabans');

        $hasilUjiansSelesai = HasilUjian::where('ujian_id', $ujian->id)
                            ->whereNotNull('finished_at')
                            ->with('user')
                            ->latest('finished_at')
                            ->get();

        return view('ujian.show', compact('ujian', 'hasilUjiansSelesai'));
    }

    // HAPUS UJIAN
    public function destroy(Ujian $ujian)
    {
        if ($ujian->user_id !== Auth::id()) abort(403);

        foreach ($ujian->soals as $soal) {
            if ($soal->image_path) {
                Storage::disk('public')->delete($soal->image_path);
            }
        }

        $ujian->delete();
        return redirect()->route('ujian.index')->with('status', 'Ujian berhasil dihapus.');
    }

    // ===========================
    // EDIT & UPDATE UJIAN
    // ===========================
    public function edit(Ujian $ujian)
    {
        if ($ujian->user_id !== Auth::id()) abort(403);
        return view('ujian.edit', compact('ujian'));
    }

    public function update(Request $request, Ujian $ujian)
    {
        if ($ujian->user_id !== Auth::id()) abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi_menit' => 'required|integer|min:1',
            'available_from' => 'required|date',
            'available_to' => 'required|date|after:available_from',
        ]);

        $ujian->update($request->all());

        return redirect()->route('ujian.show', $ujian)
            ->with('status', 'Ujian berhasil diperbarui.');
    }

    // ===========================
    // SOAL
    // ===========================
    public function createSoal(Ujian $ujian)
    {
        if ($ujian->user_id !== Auth::id()) abort(403);
        return view('soal.create', compact('ujian'));
    }

    public function storeSoal(Request $request, Ujian $ujian)
    {
        if ($ujian->user_id !== Auth::id()) abort(403);

        $request->validate([
            // Tipe soal di form Blade sekarang menggunakan 'pilihan_ganda' atau 'esai'
            'type' => 'required|in:pilihan_ganda,esai', 
            'pertanyaan' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
            // Aturan Validasi untuk Pilihan Ganda (PG)
            'pilihan' => 'required_if:type,pilihan_ganda|array|min:2', // Wajib ada minimal 2 opsi jika tipe PG
            'pilihan.*' => 'required_if:type,pilihan_ganda|string|max:255', // Setiap opsi wajib diisi dan memiliki batas panjang
            'jawaban_benar' => 'required_if:type,pilihan_ganda|integer|min:0', // Kunci jawaban wajib dipilih
        ]);

        $path = null;
        if ($request->hasFile('gambar_soal')) {
            $path = $request->file('gambar_soal')->store('soal_images', 'public');
        }

        DB::transaction(function () use ($request, $ujian, $path) {
            $soal = $ujian->soals()->create([
                'pertanyaan' => $request->pertanyaan,
                'image_path' => $path,
                'type' => $request->type, 
            ]);

            if ($request->type === 'pilihan_ganda') {
                foreach ($request->pilihan as $key => $teksPilihan) {
                    // Cek apakah teks pilihan tidak kosong (tambahan keamanan)
                    if (trim($teksPilihan) !== '') {
                        $soal->pilihanJawabans()->create([
                            'teks_pilihan' => $teksPilihan,
                            // Pastikan perbandingan menggunakan tipe data integer
                            'apakah_benar' => ((int)$key === (int)$request->jawaban_benar), 
                        ]);
                    }
                }
            }
        });

        return redirect()->route('ujian.show', $ujian)->with('status', 'Soal berhasil ditambahkan.');
    }

    public function editSoal(Soal $soal)
    {
        if ($soal->ujian->user_id !== Auth::id()) abort(403);

        $soal->load('pilihanJawabans');
        return view('soal.edit', compact('soal'));
    }

    public function updateSoal(Request $request, Soal $soal)
    {
        if ($soal->ujian->user_id !== Auth::id()) abort(403);

        $request->validate([
            'type' => 'required|in:pilihan_ganda,esai',
            'pertanyaan' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pilihan' => 'required_if:type,pilihan_ganda|array|min:2|nullable',
            'pilihan.*' => 'required_if:type,pilihan_ganda|string|nullable',
            'jawaban_benar' => 'required_if:type,pilihan_ganda|integer|min:0|nullable',
        ]);

        DB::transaction(function () use ($request, $soal) {

            $soal->pertanyaan = $request->pertanyaan;
            $soal->type = $request->type;

            if ($request->has('hapus_gambar') && $soal->image_path) {
                Storage::disk('public')->delete($soal->image_path);
                $soal->image_path = null;
            }

            if ($request->hasFile('gambar_soal')) {
                if ($soal->image_path) {
                    Storage::disk('public')->delete($soal->image_path);
                }
                $soal->image_path = $request->file('gambar_soal')->store('soal_images', 'public');
            }

            $soal->save();

            $soal->pilihanJawabans()->delete();

            if ($request->type === 'pilihan_ganda') {
                foreach ($request->pilihan as $key => $teksPilihan) {
                    $soal->pilihanJawabans()->create([
                        'teks_pilihan' => $teksPilihan,
                        'apakah_benar' => ($key == $request->jawaban_benar),
                    ]);
                }
            }
        });

        return redirect()->route('ujian.show', $soal->ujian_id)
            ->with('status', 'Soal berhasil diperbarui.');
    }

    public function destroySoal(Soal $soal)
    {
        $ujian_id = $soal->ujian_id;
        $ujian = Ujian::findOrFail($ujian_id);

        if ($ujian->user_id !== Auth::id()) abort(403);

        if ($soal->image_path) {
            Storage::disk('public')->delete($soal->image_path);
        }

        $soal->delete();

        return redirect()->route('ujian.show', $ujian_id)
            ->with('status', 'Soal berhasil dihapus.');
    }

    public function showHasil(HasilUjian $hasilUjian)
    {
        if ($hasilUjian->ujian->user_id !== Auth::id()) abort(403);

        $hasilUjian->load('user', 'ujian.soals.pilihanJawabans');
        $jawabanMap = $hasilUjian->jawabanMahasiswas->keyBy('soal_id');

        return view('ujian.hasil', compact('hasilUjian', 'jawabanMap'));
    }

    public function showKoreksi(HasilUjian $hasilUjian)
    {
        if ($hasilUjian->ujian->user_id !== Auth::id()) abort(403);

        if ($hasilUjian->skor_esai !== null) {
            return redirect()->route('ujian.hasil', $hasilUjian)
                ->with('status', 'Ujian ini sudah dikoreksi.');
        }

        $hasilUjian->load('user', 'ujian');

        $jawabanEsai = $hasilUjian->jawabanMahasiswas()
            ->whereHas('soal', fn($q) => $q->where('type', 'esai'))
            ->with('soal')
            ->get();

        if ($jawabanEsai->isEmpty()) {
            return redirect()->route('ujian.hasil', $hasilUjian)
                ->with('status', 'Tidak ada soal esai.');
        }

        return view('ujian.koreksi', compact('hasilUjian', 'jawabanEsai'));
    }

    public function simpanKoreksi(Request $request, HasilUjian $hasilUjian)
    {
        if ($hasilUjian->ujian->user_id !== Auth::id()) abort(403);

        $request->validate([
            'skor_esai' => 'required|array',
            'skor_esai.*' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $totalSkorEsai = array_sum($request->skor_esai);
            $skorPg = $hasilUjian->skor_pg ?? 0;
            $totalSkorAkhir = $skorPg + $totalSkorEsai;

            $hasilUjian->update([
                'skor_esai' => $totalSkorEsai,
                'total_skor' => $totalSkorAkhir,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['db_error' => 'Gagal menyimpan koreksi.']);
        }

        return redirect()->route('ujian.show', $hasilUjian->ujian_id)
            ->with('status', 'Koreksi berhasil disimpan.');
    }
}
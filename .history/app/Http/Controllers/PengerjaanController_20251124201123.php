<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\JawabanMahasiswa; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon; 

class PengerjaanController extends Controller
{
    
    public function start(Ujian $ujian)
    {
        // --- Validasi Jadwal Ujian ---
        $now = now();

        if ($ujian->available_from && $now->lt($ujian->available_from)) {
            return redirect()->route('ujian.index')
                ->with('status', 'Ujian ini belum dibuka.');
        }

        if ($ujian->available_to && $now->gt($ujian->available_to)) {
            return redirect()->route('ujian.index')
                ->with('status', 'Ujian ini sudah ditutup.');
        }

        // --- Validasi apakah sudah pernah selesai ---
        $hasilSebelumnya = HasilUjian::where('user_id', Auth::id())
                                ->where('ujian_id', $ujian->id)
                                ->whereNotNull('finished_at') 
                                ->first();

        if ($hasilSebelumnya) {
             return redirect()->route('pengerjaan.result', $ujian)
                 ->with('status', 'Anda sudah pernah menyelesaikan ujian ini.');
        }

        // --- Validasi Pengerjaan yang masih berlangsung ---
        $pengerjaanAktif = HasilUjian::where('user_id', Auth::id())
                                ->where('ujian_id', $ujian->id)
                                ->whereNull('finished_at') 
                                ->first();
        
        if ($pengerjaanAktif) {
            return redirect()->route('pengerjaan.show', $pengerjaanAktif);
        }

        $ujian->loadCount('soals');
        return view('pengerjaan.start', compact('ujian'));
    }

    // ===============================
    public function begin(Request $request, Ujian $ujian)
    {
        // --- 1. VALIDASI TOKEN ---
        $request->validate([
            'token' => 'required|string'
        ]);

        // cek kecocokan token (case-insensitive)
        if (strtoupper($request->token) !== strtoupper($ujian->token)) {
            return back()->withErrors([
                'token' => 'Token salah! Silakan minta token yang benar ke Dosen.'
            ]);
        }

        // --- 2. CEK PENGERJAAN AKTIF ---
        $pengerjaanAktif = HasilUjian::where('user_id', Auth::id())
                                ->where('ujian_id', $ujian->id)
                                ->whereNull('finished_at')
                                ->first();
        
        if ($pengerjaanAktif) {
            return redirect()->route('pengerjaan.show', $pengerjaanAktif);
        }

        // --- 3. BUAT PENGERJAAN BARU ---
        $hasilUjian = HasilUjian::create([
            'user_id' => Auth::id(),
            'ujian_id' => $ujian->id,
            'started_at' => now(),
            'skor_pg' => null,
            'skor_esai' => null,
            'total_skor' => null,
        ]);
        
        return redirect()->route('pengerjaan.show', $hasilUjian);
    }
    // ===============================



    public function show(HasilUjian $hasilUjian)
    {
        if ($hasilUjian->user_id !== Auth::id()) {
            abort(403);
        }

        if ($hasilUjian->finished_at) {
             return redirect()->route('pengerjaan.result', $hasilUjian->ujian_id)
                 ->with('status', 'Anda sudah menyelesaikan ujian ini.');
        }

        $hasilUjian->load('ujian.soals.pilihanJawabans');
        $endTime = $hasilUjian->started_at->addMinutes($hasilUjian->ujian->durasi_menit);

        if (now()->greaterThan($endTime)) {
             return $this->forceSubmit($hasilUjian);
        }

        $jawabanTersimpan = JawabanMahasiswa::where('hasil_ujian_id', $hasilUjian->id)
                            ->get()
                            ->keyBy('soal_id');

        return view('pengerjaan.show', compact('hasilUjian', 'endTime', 'jawabanTersimpan'));
    }



    public function submit(Request $request, HasilUjian $hasilUjian)
    {
        if ($hasilUjian->user_id !== Auth::id() || $hasilUjian->finished_at) {
            abort(403, 'Akses ditolak.');
        }

        $endTime = $hasilUjian->started_at->addMinutes($hasilUjian->ujian->durasi_menit);
        if (now()->greaterThan($endTime->addSeconds(5))) {
            return $this->forceSubmit($hasilUjian, 'Waktu Anda habis. Jawaban terakhir mungkin tidak tersimpan.');
        }

        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*' => 'nullable',
        ]);

        $ujian = $hasilUjian->ujian->load('soals.pilihanJawabans'); 
        
        try {
            DB::beginTransaction();

            foreach ($ujian->soals as $soal) {
                $jawaban_data = $request->jawaban[$soal->id] ?? null;
                
                $data_to_save = [
                    'pilihan_jawaban_id' => null,
                    'jawaban_esai' => null,
                ];

                if ($soal->type == 'pilihan_ganda') {
                    $data_to_save['pilihan_jawaban_id'] = $jawaban_data['pilihan_id'] ?? null;
                } elseif ($soal->type == 'esai') {
                    $data_to_save['jawaban_esai'] = $jawaban_data['jawaban_esai'] ?? null;
                }
                
                JawabanMahasiswa::updateOrCreate(
                    [
                        'hasil_ujian_id' => $hasilUjian->id,
                        'soal_id' => $soal->id,
                    ],
                    $data_to_save
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['db_error' => 'Terjadi kesalahan saat menyimpan jawaban Anda. Silakan coba lagi.']);
        }
        
        // --- Hitung Skor PG ---
        $total_soal_pg = 0;
        $jawaban_benar = 0;

        foreach ($ujian->soals as $soal) {
            if ($soal->type == 'pilihan_ganda') {
                $total_soal_pg++;
                
                $jawaban_data = $request->jawaban[$soal->id] ?? null;
                $id_pilihan_user = $jawaban_data['pilihan_id'] ?? null;

                if ($id_pilihan_user) {
                    $pilihan_benar = $soal->pilihanJawabans->firstWhere('apakah_benar', true);
                    if ($pilihan_benar && $pilihan_benar->id == $id_pilihan_user) {
                        $jawaban_benar++;
                    }
                }
            }
        }

        $skor_pg = ($total_soal_pg > 0) ? ($jawaban_benar / $total_soal_pg) * 100 : 0;
        
        $ada_esai = $ujian->soals->contains('type', 'esai');

        $dataUpdate = [
            'skor_pg' => $skor_pg,
            'finished_at' => now(),
        ];

        if ($ada_esai) {
            $dataUpdate['skor_esai'] = null;
            $dataUpdate['total_skor'] = null;
        } else {
            $dataUpdate['skor_esai'] = 0;
            $dataUpdate['total_skor'] = $skor_pg;
        }

        $hasilUjian->update($dataUpdate);

        $pesan = 'Ujian telah selesai dikerjakan! Skor Anda: ' . number_format($skor_pg, 0);
        if ($ada_esai) {
            $pesan = 'Ujian selesai. Skor Pilihan Ganda Anda: ' . number_format($skor_pg, 0) . '. Jawaban esai akan dikoreksi oleh dosen.';
        }

        return redirect()->route('pengerjaan.result', $ujian->id)->with('status', $pesan);
    }


    public function result(Ujian $ujian)
    {
        $hasil = HasilUjian::where('user_id', Auth::id())
                        ->where('ujian_id', $ujian->id)
                        ->whereNotNull('finished_at') 
                        ->latest('finished_at')
                        ->firstOrFail();

        return view('pengerjaan.result', compact('ujian', 'hasil'));
    }


    private function forceSubmit(HasilUjian $hasilUjian, $message = 'Waktu habis! Ujian diselesaikan secara otomatis.')
    {
         if (!$hasilUjian->finished_at) {

            $hasilUjian->loadMissing('ujian.soals');
            $ada_esai = $hasilUjian->ujian->soals->contains('type', 'esai');

            $skor_pg = $hasilUjian->skor_pg ?? 0;

            $dataUpdate = [
                'finished_at' => now(),
                'skor_pg' => $skor_pg,
            ];

            if ($ada_esai) {
                $dataUpdate['skor_esai'] = $hasilUjian->skor_esai;
                $dataUpdate['total_skor'] = $hasilUjian->total_skor;
            } else {
                $dataUpdate['skor_esai'] = 0;
                $dataUpdate['total_skor'] = $skor_pg;
            }
            
            $hasilUjian->update($dataUpdate);
         }

        return redirect()->route('pengerjaan.result', $hasilUjian->ujian_id)
            ->with('status', $message);
    }
}

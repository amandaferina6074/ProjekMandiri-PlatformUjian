<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; 

class UjianController extends Controller
{
   
    public function index() {
        $ujians = Ujian::withCount('soals')->latest()->paginate(5);
        return view('ujian.index', compact('ujians'));
    }
    public function create() {
        return view('ujian.create');
    }
    public function store(Request $request) {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi_menit' => 'required|integer|min:1',
            'available_from' => 'required|date',
            'available_to' => 'required|date|after:available_from',
        ]);
        $ujian = Ujian::create($request->all());
        return redirect()->route('ujian.show', $ujian)->with('status', 'Ujian berhasil dibuat! Silakan tambahkan soal.');
    }
    public function show(Ujian $ujian) {
        $ujian->load('soals.pilihanJawabans');
        return view('ujian.show', compact('ujian'));
    }
    public function createSoal(Ujian $ujian) {
        return view('soal.create', compact('ujian'));
    }


    public function storeSoal(Request $request, Ujian $ujian)
    {
        $request->validate([
            'type' => 'required|in:pilihan_ganda,esai',
            'pertanyaan' => 'required|string',
            'pilihan' => 'required_if:type,pilihan_ganda|array|min:4|nullable',
            'pilihan.*' => 'required_if:type,pilihan_ganda|string|nullable',
            'jawaban_benar' => 'required_if:type,pilihan_ganda|integer|min:0|max:3|nullable',
        ]);


        DB::transaction(function () use ($request, $ujian) { 
            
            $soal = $ujian->soals()->create([
                'pertanyaan' => $request->pertanyaan,
                'type' => $request->type,
            ]);

            if ($request->type === 'pilihan_ganda') {
                foreach ($request->pilihan as $key => $teksPilihan) {
                    $soal->pilihanJawabans()->create([
                        'teks_pilihan' => $teksPilihan,
                        'apakah_benar' => ($key == $request->jawaban_benar),
                    ]);
                }
            }
        });
        return redirect()->route('ujian.show', $ujian)->with('status', 'Soal berhasil ditambahkan.');
    }
    
    public function destroy(Ujian $ujian)
    {
        
        $ujian->delete();
        return redirect()->route('ujian.index')->with('status', 'Ujian berhasil dihapus.');
    }

    public function editSoal(Soal $soal)
    {
        $soal->load('pilihanJawabans');
        return view('soal.edit', compact('soal'));
    }

    public function updateSoal(Request $request, Soal $soal)
    {
        $request->validate([
            'type' => 'required|in:pilihan_ganda,esai',
            'pertanyaan' => 'required|string',
            'pilihan' => 'required_if:type,pilihan_ganda|array|min:4|nullable',
            'pilihan.*' => 'required_if:type,pilihan_ganda|string|nullable',
            'jawaban_benar' => 'required_if:type,pilihan_ganda|integer|min:0|max:3|nullable',
        ]);

        DB::transaction(function () use ($request, $soal) {
            
            $soal->pertanyaan = $request->pertanyaan;
            $soal->type = $request->type;

            
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

        return redirect()->route('ujian.show', $soal->ujian_id)->with('status', 'Soal berhasil diperbarui.');
    }

    public function destroySoal(Soal $soal)
    {
        $ujian_id = $soal->ujian_id;

        $soal->delete();

        return redirect()->route('ujian.show', $ujian_id)->with('status', 'Soal berhasil dihapus.');
    }
    
    public function edit(Ujian $ujian) {}
    public function update(Request $request, Ujian $ujian) {}
}
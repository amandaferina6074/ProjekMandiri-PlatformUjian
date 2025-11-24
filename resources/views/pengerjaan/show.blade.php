@extends('layouts.app-new')
@section('title', 'Mengerjakan: ' . $hasilUjian->ujian->judul)

@section('content')

{{-- Timer ujian (Sticky Top) --}}
<div 
    x-data="pengerjaanTimer('{{ $endTime->toIso8601String() }}')" 
    x-init="initTimer()"
    class="card shadow-sm mb-4 sticky-top"
    style="z-index: 1020;" {{-- Pastikan z-index lebih kecil dari modal (1050) --}}
>
    <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-truncate" style="max-width: 70%;">{{ $hasilUjian->ujian->judul }}</h5>
        <div class="bg-danger text-white px-3 py-2 rounded shadow-sm d-flex align-items-center">
            <i class="bi bi-alarm-fill me-2"></i>
            <span class="fw-bold fs-5" x-text="displayTime">00:00</span>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4 p-md-5">
        {{-- Form menjawab soal --}}
        <form id="form-ujian" action="{{ route('pengerjaan.submit', $hasilUjian) }}" method="POST">
            @csrf
            
            @foreach ($hasilUjian->ujian->soals as $key => $soal)
                <div class="mb-4 pb-4 border-bottom">
                    <div class="d-flex align-items-start">
                        <span class="fw-bold fs-5 me-2">{{ $key + 1 }}.</span>
                        <div class="flex-grow-1">
                            {{-- Pertanyaan --}}
                            <div class="fw-bold fs-5 mb-2">{!! $soal->pertanyaan !!}</div>
                    
                            {{-- Gambar soal --}}
                            @if ($soal->image_path)
                            <div class="mb-3">
                                <img src="{{ Storage::url($soal->image_path) }}" alt="Gambar Soal" class="img-fluid rounded" style="max-height: 300px; width: auto;">
                            </div>
                            @endif

                            {{-- Pilihan Ganda --}}
                            @if ($soal->type == 'pg')
                                <div class="ps-md-4">
                                    @foreach ($soal->pilihanJawabans as $pilihan)
                                    <div class="form-check fs-5 mb-2">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="jawaban[{{ $soal->id }}][pilihan_id]" 
                                               id="pilihan-{{ $pilihan->id }}" 
                                               value="{{ $pilihan->id }}"
                                               {{ (old('jawaban.'.$soal->id.'.pilihan_id') ?? $jawabanTersimpan->get($soal->id)?->pilihan_jawaban_id) == $pilihan->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pilihan-{{ $pilihan->id }}">
                                            {{ $pilihan->teks_pilihan }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            
                            {{-- Esai --}}
                            @elseif ($soal->type == 'esai')
                                <div class="ps-md-4">
                                    <label for="jawaban-esai-{{ $soal->id }}" class="form-label text-muted small">Jawaban esai:</label>
                                    <textarea class="form-control" 
                                              id="jawaban-esai-{{ $soal->id }}" 
                                              name="jawaban[{{ $soal->id }}][jawaban_esai]" 
                                              rows="5">{{ old('jawaban.'.$soal->id.'.jawaban_esai') ?? $jawabanTersimpan->get($soal->id)?->jawaban_esai }}</textarea>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Tombol Submit (Hanya memicu Modal) --}}
            <div class="text-center mt-4">
                <button type="button" class="btn btn-success btn-lg fw-bold px-5" onclick="konfirmasiSubmit()">
                    <i class="bi bi-check-circle-fill"></i> Selesaikan Ujian
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Konfirmasi (DI LUAR FORM) --}}
<div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Penyelesaian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Yakin ingin menyelesaikan ujian ini? Jawaban yang dikirim tidak bisa diubah.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitFormUjian()">Ya, kirim</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    var konfirmasiModal;

    document.addEventListener('DOMContentLoaded', function () {
         var modalEl = document.getElementById('konfirmasiModal');
         
         // [FIX UTAMA] Pindahkan modal ke body agar tidak tertutup elemen lain
         if(modalEl) {
             document.body.appendChild(modalEl);
         }

         // Inisialisasi Bootstrap Modal
         konfirmasiModal = new bootstrap.Modal(modalEl);
    });

    function konfirmasiSubmit() {
        konfirmasiModal.show();
    }

    function submitFormUjian() {
        konfirmasiModal.hide();
        document.getElementById('form-ujian').submit();
    }

    // Logic Timer Ujian
    function pengerjaanTimer(endTime) {
        return {
            endTime: new Date(endTime),
            displayTime: '00:00:00',
            interval: null,
            waktuHabis: false,

            initTimer() {
                this.updateTime(); 
                this.interval = setInterval(() => this.updateTime(), 1000);
            },

            updateTime() {
                if (this.waktuHabis) return;
                const now = new Date();
                const remaining = this.endTime - now;

                if (remaining <= 0) {
                    clearInterval(this.interval);
                    this.displayTime = '00:00:00';
                    this.waktuHabis = true;
                    this.submitFormOtomatis(); 
                } else {
                    const hours = Math.floor(remaining / (1000 * 60 * 60));
                    const minutes = Math.floor((remaining / 1000 / 60) % 60);
                    const seconds = Math.floor((remaining / 1000) % 60);
                    this.displayTime = `${this.pad(hours)}:${this.pad(minutes)}:${this.pad(seconds)}`;
                }
            },

            pad(num) {
                return num < 10 ? '0' + num : num;
            },

            submitFormOtomatis() {
                console.log('Waktu habis! Mengirim form...');
                const form = document.getElementById('form-ujian');
                if (form) {
                    const tombolSubmit = form.querySelector('button[type="button"]');
                    if(tombolSubmit) tombolSubmit.disabled = true;
                    
                    if(konfirmasiModal) konfirmasiModal.hide();

                    form.submit();
                }
            }
        };
    }
</script>
@endpush
<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\PengerjaanController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/verify-otp', [OtpController::class, 'create'])->name('otp.verify');
Route::post('/verify-otp', [OtpController::class, 'store'])->name('otp.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UjianController::class, 'index'])->name('dashboard');
    Route::get('/ujian-panel', [UjianController::class, 'index'])->name('ujian.index');
});

Route::middleware(['auth', CheckRole::class . ':admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            $totalUsers = User::count();
            $totalDosen = User::where('role', 'dosen')->count();
            $totalMahasiswa = User::where('role', 'mahasiswa')->count();
            $totalAdmin = User::where('role', 'admin')->count();
            $resetRequests = User::whereNotNull('password_reset_requested_at')->count();

            return view('admin.dashboard', compact(
                'totalUsers',
                'totalDosen',
                'totalMahasiswa',
                'totalAdmin',
                'resetRequests'
            ));
        })->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/reset', [UserController::class, 'resetPassword'])->name('users.reset');
    });

Route::middleware(['auth', CheckRole::class . ':dosen'])->group(function () {

    Route::resource('ujian', UjianController::class)->except(['index']);

    Route::get('/ujian/hasil/{hasilUjian}', [UjianController::class, 'showHasil'])->name('ujian.hasil');
    Route::get('/ujian/koreksi/{hasilUjian}', [UjianController::class, 'showKoreksi'])->name('ujian.koreksi');
    Route::post('/ujian/koreksi/{hasilUjian}', [UjianController::class, 'simpanKoreksi'])->name('ujian.simpanKoreksi');

    Route::prefix('ujian/{ujian}')->group(function () {
        Route::get('/soal/create', [UjianController::class, 'createSoal'])->name('soal.create');
        Route::post('/soal', [UjianController::class, 'storeSoal'])->name('soal.store');
    });

    Route::get('/soal/{soal}/edit', [UjianController::class, 'editSoal'])->name('soal.edit');
    Route::put('/soal/{soal}', [UjianController::class, 'updateSoal'])->name('soal.update');
    Route::delete('/soal/{soal}', [UjianController::class, 'destroySoal'])->name('soal.destroy');
});

Route::middleware(['auth', CheckRole::class . ':mahasiswa'])->group(function () {

    Route::post('/ujian/search', [UjianController::class, 'searchByToken'])->name('ujian.search');

    Route::prefix('pengerjaan')->as('pengerjaan.')->group(function () {
        Route::get('/{ujian}/start', [PengerjaanController::class, 'start'])->name('start');
        Route::post('/{ujian}/begin', [PengerjaanController::class, 'begin'])->name('begin');
        Route::get('/{hasilUjian}', [PengerjaanController::class, 'show'])->name('show');
        Route::post('/{hasilUjian}/submit', [PengerjaanController::class, 'submit'])->name('submit');
        Route::get('/{ujian}/result', [PengerjaanController::class, 'result'])->name('result');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

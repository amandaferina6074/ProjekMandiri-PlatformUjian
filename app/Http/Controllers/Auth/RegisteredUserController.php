<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Show Register Page
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * HANDLE REGISTRASI + KIRIM OTP
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi input
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'role'        => ['required', 'string', 'in:dosen,mahasiswa,admin'],
            'kode_dosen'  => ['nullable', 'string'],
            'kode_admin'  => ['nullable', 'string'],
        ]);

        // 2. Validasi kode DOSEN
        if ($request->role === 'dosen' && $request->kode_dosen !== 'DOSEN2025') {
            throw ValidationException::withMessages([
                'kode_dosen' => 'Kode Validasi Dosen salah!',
            ]);
        }

        // 3. Validasi kode ADMIN
        if ($request->role === 'admin' && $request->kode_admin !== 'SUPERADMIN') {
            throw ValidationException::withMessages([
                'kode_admin' => 'Kode Admin Salah! Anda tidak berhak daftar sebagai admin.',
            ]);
        }

        // 4. Generate OTP acak 6 digit
        $otp = rand(100000, 999999);

        // 5. Simpan user baru
        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'role'            => $request->role,
            'otp_code'        => $otp,
            'otp_expires_at'  => Carbon::now()->addMinutes(5),
        ]);

        event(new Registered($user));

        // 6. Kirim OTP via email
        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            Log::error("Gagal mengirim OTP: " . $e->getMessage());
        }

        // 7. Bersihkan session OTP (jika ada dari login lama)
        $request->session()->forget('auth_email_otp');

        // 8. Redirect ke login (bukan ke halaman OTP)
        return redirect()->route('otp.verify')
            ->with('status', 'Pendaftaran berhasil! Silakan login dan masukkan kode OTP berada di log laravel.');
    }
}

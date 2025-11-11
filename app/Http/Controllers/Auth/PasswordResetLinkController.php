<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Menampilkan halaman permintaan link reset password.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Memproses permintaan pengiriman link reset password.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi bahwa email wajib diisi dan harus berupa email yang valid.
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Mengirim link reset password ke email pengguna.
        // Setelah percobaan pengiriman, akan mengecek responsnya
        // untuk menentukan pesan apa yang harus ditampilkan ke pengguna.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Jika link berhasil dikirim, kembalikan pesan sukses.
        // Jika gagal, kembalikan ke halaman sebelumnya dengan error.
        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}

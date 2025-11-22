<?php  

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function create()
    {
        return view('auth.otp-verify');
    }

    public function store(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['otp' => 'Sesi habis, silakan login ulang.']);
        }

        if ($request->otp != $user->otp_code) {
            return back()->withErrors(['otp' => 'Kode OTP salah!']);
        }

        $user->otp_code = null;
        $user->save();

        // Redirect sesuai role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'dosen') {
            return redirect()->route('ujian.index');
        }

        return redirect()->intended(route('dashboard'));
    }
}

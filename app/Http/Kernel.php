<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Middleware global yang dijalankan di setiap request
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class, // Mengatur proxy server
        \Illuminate\Http\Middleware\HandleCors::class, // Menangani CORS (Cross-Origin Resource Sharing)
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class, // Blokir akses saat mode maintenance
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, // Validasi ukuran POST request
        \App\Http\Middleware\TrimStrings::class, // Hapus spasi berlebih dari input
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // Ubah string kosong jadi null
    ];

    // Middleware group untuk web dan API
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class, // Enkripsi cookie
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // Tambahkan cookie ke response
            \Illuminate\Session\Middleware\StartSession::class, // Mulai session
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // Bagikan error ke view
            \App\Http\Middleware\VerifyCsrfToken::class, // Cegah serangan CSRF
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Binding model otomatis di route
        ],

        'api' => [
            // Middleware untuk API request
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api', // Batasi jumlah request API
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    // Alias middleware agar mudah dipanggil di route
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class, // Autentikasi user
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class, // Autentikasi berbasis session
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class, // Atur header cache
        'can' => \Illuminate\Auth\Middleware\Authorize::class, // Cek izin (authorization)
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // Redirect jika user sudah login
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class, // Konfirmasi ulang password
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class, // Middleware untuk request precognitive
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class, // Validasi tanda tangan URL
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class, // Batasi jumlah request
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Pastikan email user sudah diverifikasi

        // Middleware custom untuk pengecekan role user
        'role' => \App\Http\Middleware\CheckRole::class,
    ];
}

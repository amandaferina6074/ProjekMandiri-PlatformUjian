<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Platform Ujian</title>
    {{-- Font & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #A64D79;
            --primary-hover: #85305b;
            --bg-gradient-start: #f5e6f0;
            --bg-gradient-end: #e8d5e5;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 40px 30px;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(166, 77, 121, 0.15);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background-color: #fdf2f8;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 20px;
        }

        h2 { margin: 0 0 10px; color: #333; font-weight: 700; }
        p { color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 30px; }

        /* Input Styling */
        .form-group { text-align: left; margin-bottom: 20px; }
        label { display: block; font-size: 14px; font-weight: 600; color: #444; margin-bottom: 8px; margin-left: 10px;}
        
        input[type="email"] {
            width: 100%;
            padding: 12px 20px;
            border-radius: 50px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            font-size: 14px;
            box-sizing: border-box; 
            transition: 0.3s;
            outline: none;
        }
        input[type="email"]:focus {
            border-color: var(--primary);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(166, 77, 121, 0.1);
        }

        /* Button Styling */
        button {
            width: 100%;
            padding: 14px;
            border-radius: 50px;
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: 0.3s;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(166, 77, 121, 0.3);
        }
        button:hover { background-color: var(--primary-hover); transform: translateY(-2px); }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #888;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        .back-link:hover { color: var(--primary); }

        .status-msg {
            background-color: #d1fae5; color: #065f46;
            padding: 10px; border-radius: 15px; font-size: 13px; margin-bottom: 20px;
        }
        .error-msg {
            color: #dc2626; font-size: 12px; margin-top: 5px; margin-left: 10px;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="icon-wrapper">
            <i class="bi bi-question-circle-fill"></i>
        </div>

        <h2>Lupa Password?</h2>
        <p>
            Jangan khawatir. Masukkan email Anda di bawah ini, dan sistem akan <strong>mengirim notifikasi ke Admin</strong> untuk mereset password Anda secara manual.
        </p>

        @if (session('status'))
            <div class="status-msg">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Terdaftar</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                @if ($errors->get('email'))
                    <div class="error-msg">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <button type="submit">
                Minta Admin Reset Password
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Login
        </a>
    </div>

</body>
</html>
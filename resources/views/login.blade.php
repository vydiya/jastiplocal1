<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JastGo - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body {
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        /* Container Utama */
        .container {
            display: flex;
            width: 90%;
            max-width: 1000px;
            height: 600px; /* Tinggi fix agar rapi */
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        /* Bagian Kiri (Biru) */
        .left-panel {
            flex: 1;
            background-color: #2563EB; /* Biru terang sesuai gambar */
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            position: relative;
        }
        .left-panel h1 { font-size: 2rem; font-weight: 700; margin-bottom: 10px; }
        .brand-name { font-size: 3rem; font-weight: 700; color: #FACC15; /* Kuning */ margin-bottom: 15px; }
        .tagline { font-size: 0.9rem; opacity: 0.9; line-height: 1.5; max-width: 80%; }

        /* Bagian Kanan (Form) */
        .right-panel {
            flex: 1;
            background: #EFF6FF; /* Biru sangat muda */
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        /* Dekorasi Blob Background */
        .right-panel::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 200px; height: 200px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            z-index: 0;
        }
        .right-panel::after {
            content: '';
            position: absolute;
            bottom: -30px; left: -30px;
            width: 150px; height: 150px;
            background: rgba(37, 99, 235, 0.15);
            border-radius: 50%;
            z-index: 0;
        }

        .form-content { position: relative; z-index: 1; width: 100%; max-width: 350px; margin: 0 auto; }
        .form-title { text-align: center; color: #2563EB; font-size: 1.8rem; font-weight: 700; margin-bottom: 30px; }

        .form-group { margin-bottom: 15px; }
        .form-label { display: block; color: #2563EB; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; margin-left: 10px; }
        
        /* Input Style sesuai Gambar */
        .form-input {
            width: 100%;
            padding: 12px 20px;
            border-radius: 25px; /* Rounded pill shape */
            border: 1px solid #BFDBFE;
            background-color: #DBEAFE; /* Biru input */
            color: #1e3a8a;
            outline: none;
            transition: all 0.3s;
        }
        .form-input:focus { border-color: #2563EB; background-color: #fff; }

        .btn-submit {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 25px;
            border: none;
            background-color: #2563EB;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-submit:hover { background-color: #1d4ed8; }

        .link-text { text-align: center; margin-top: 15px; font-size: 0.9rem; color: #4b5563; }
        .link-text a { color: #2563EB; text-decoration: none; font-weight: 600; }
        
        .alert { padding: 10px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 15px; text-align: center; }
        .alert-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .error-msg { color: #ef4444; font-size: 0.75rem; margin-left: 15px; margin-top: 2px; }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .container { flex-direction: column; height: auto; }
            .left-panel { padding: 30px 20px; }
            .left-panel h1 { font-size: 1.5rem; }
            .brand-name { font-size: 2rem; }
            .right-panel { padding: 40px 20px; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="left-panel">
            <h1>SELAMAT DATANG!</h1>
            <div class="brand-name">JastGo</div>
            <p class="tagline">Platform jastip yang cepat, aman, dan mudah digunakan.</p>
        </div>

        <div class="right-panel">
            <div class="form-content">
                <h2 class="form-title">Login</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
                        @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn-submit">Login</button>

                    <div class="link-text">
                        Belum punya Akun? <a href="{{ route('register') }}">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
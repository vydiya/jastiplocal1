<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JastiGo - Login Admin</title>
    <!-- Ganti dengan link CSS template admin Anda -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* Gaya dasar untuk form login */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Admin JastiGo</h2>

        <!-- Gunakan route dengan prefix admin sesuai routes/web.php -->
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <!-- Pesan Error Global dari Controller -->
            @if ($errors->any())
                <div class="alert-danger">
                    <ul style="margin:0;padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert-success" style="margin-bottom:10px;color:green;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary">LOGIN</button>
            </div>
        </form>
    </div>
</body>
</html>

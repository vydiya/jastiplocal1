{{-- resources/views/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JastGo - Login</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<style>
  body{ display:flex;justify-content:center;align-items:center;min-height:100vh;background:#f8f9fa;font-family:Arial, sans-serif;}
  .login-box{background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,.08);width:360px;}
  .login-box h2{ text-align:center;margin-bottom:18px;color:#007bff; }
  .form-group{ margin-bottom:12px; }
  input[type="email"],input[type="password"]{ width:100%;padding:10px;border:1px solid #ced4da;border-radius:6px;box-sizing:border-box;}
  .btn-primary{ width:100%;padding:10px;background:#007bff;color:#fff;border:none;border-radius:6px;font-size:16px;cursor:pointer;}
  .alert-danger{ color:#721c24;background:#f8d7da;padding:8px;border-radius:6px;margin-bottom:10px;border:1px solid #f5c6cb;}
  .text-error{ color:#c00;font-size:.9rem;margin-top:6px;}
  .link{ margin-top:10px;text-align:center;font-size:14px; }
</style>
</head>
<body>
  <div class="login-box">
    <h2>Login</h2>

    <form method="POST" action="{{ route('login.post') }}">
      @csrf

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
        <div style="margin-bottom:10px;color:green;">{{ session('success') }}</div>
      @endif

      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email') <div class="text-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @error('password') <div class="text-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <button type="submit" class="btn-primary">LOGIN</button>
      </div>

      <div class="link">
        Belum punya akun? <a href="{{ route('register') }}">Register</a>
      </div>

    </form>
  </div>
</body>
</html>

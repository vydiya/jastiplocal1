{{-- resources/views/register.blade.php (Tema Diselaraskan dengan Login) --}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JastGo - Register</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<style>
  body{ display:flex;justify-content:center;align-items:center;min-height:100vh;background:#f8f9fa;font-family:Arial, sans-serif;}
  .login-box{background:#fff;padding:30px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,.08);width:360px;}
  
  /* PERUBAHAN UTAMA: Ubah warna H2 dari hijau ke biru (#007bff) */
  .login-box h2{ text-align:center;margin-bottom:18px;color:#007bff; } 
  
  .form-group{ margin-bottom:12px; }
  input[type="text"],input[type="email"],input[type="password"]{
    width:100%;padding:10px;border:1px solid #ced4da;border-radius:6px;box-sizing:border-box;
  }
  
  /* PERUBAHAN UTAMA: Ubah background tombol dari hijau ke biru (#007bff) */
  .btn-primary{
    width:100%;padding:10px;background:#007bff;color:#fff;border:none;border-radius:6px;
    font-size:16px;cursor:pointer;
  }
  
  .alert-danger{
    color:#721c24;background:#f8d7da;padding:8px;border-radius:6px;margin-bottom:10px;border:1px solid #f5c6cb;
  }
  .text-error{ color:#c00;font-size:.9rem;margin-top:6px;}
  .link{ margin-top:10px;text-align:center; }
</style>
</head>
<body>
  <div class="login-box">
    <h2>Register</h2>

    <form method="POST" action="{{ route('register.post') }}">
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

      <div class="form-group">
        <label for="name">Nama / Username</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
      </div>

      <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
      </div>

      <div class="form-group">
        <label for="no_hp">Nomor HP</label>
        <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}">
      </div>

      <div class="form-group">
        <label for="alamat">Alamat</label>
        <input id="alamat" type="text" name="alamat" value="{{ old('alamat') }}">
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
      </div>

      <div class="form-group">
        <button type="submit" class="btn-primary">REGISTER</button>
      </div>

      <div class="link">
        Sudah punya akun? <a href="{{ route('login') }}">Login</a>
      </div>

    </form>
  </div>
</body>
</html>
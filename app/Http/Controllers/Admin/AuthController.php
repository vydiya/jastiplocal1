<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;   // <-- Tambahkan ini
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['loginForm', 'login']);
        $this->middleware('auth')->except(['loginForm', 'login']);
    }

    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard.index');
        }

        return view('admin.login');
    }

    public function login(Request $request)
{
    // ⛔  VALIDASI
    $data = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 🔐 RATE LIMITER
    $key = 'login:' . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        return back()->withErrors([
            'email' => 'Terlalu banyak percobaan! Coba lagi dalam 1 menit.'
        ]);
    }

    // 🔍 CEK USER HANYA BERDASARKAN EMAIL (tanpa peran admin)
    $user = User::where('email', $data['email'])->first();

    // 🔑 CEK PASSWORD
    if ($user && Hash::check($data['password'], $user->password)) {

        RateLimiter::clear($key); // RESET percobaan setelah sukses

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard.index');
    }

    // ❌ GAGAL LOGIN → hit rate limiter
    RateLimiter::hit($key, 60);

    return back()->withErrors([
        'email' => 'Email atau password salah.'
    ])->onlyInput('email');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}

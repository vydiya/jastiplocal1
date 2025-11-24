<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        // guest only for showing login & processing login
        $this->middleware('guest')->only(['loginForm', 'login']);
        // other actions require auth (if any)
        $this->middleware('auth')->except(['loginForm', 'login']);
    }

    /**
     * Show login form.
     * Jika user sudah login → arahkan berdasarkan role.
     */
    public function loginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard.index');
            }

            if ($user->role === 'jastiper') {
                return redirect()->route('jastiper.dashboard.index');
            }

            // fallback untuk user biasa (ubah sesuai route di projectmu)
            return redirect()->route('home'); 
        }

        // NOTE: aku pakai view('login') karena kamu punya resources/views/login.blade.php
        // jika kamu menaruh view di folder lain (mis. 'admin.login' atau 'auth.login'), ganti di sini.
        return view('login');
    }

    /**
     * Proses login dengan RateLimiter dan redirect berdasarkan role.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // key bisa gabungkan ip + email untuk granular rate limit
        $key = 'login:' . $request->ip() . '|' . strtolower($data['email']);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan! Coba lagi nanti.'
            ])->onlyInput('email');
        }

        // ambil user berdasarkan email
        $user = User::where('email', $data['email'])->first();

        // cek password
        if ($user && Hash::check($data['password'], $user->password)) {
            // sukses -> bersihkan limiter, login, regenerate session
            RateLimiter::clear($key);

            Auth::login($user);
            $request->session()->regenerate();

            // redirect berdasarkan role (sesuaikan nama route jika berbeda)
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard.index'));
            }

            if ($user->role === 'jastiper') {
                return redirect()->intended(route('jastiper.dashboard.index'));
            }

            // fallback (misal user biasa)
            return redirect()->intended(url('/'));
        }

        // gagal -> catat percobaan
        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'email' => 'Email atau password salah.'
        ])->onlyInput('email');
    }

    /**
     * Logout dan arahkan ke form login umum
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // arahkan ke route('login') — pastikan route ini ada di routes/web.php
        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Ulasan;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only([
            'loginForm',
            'login',
            'registerForm',
            'register',
        ]);

        $this->middleware('auth')->except([
            'loginForm',
            'login',
            'registerForm',
            'register',
            'landing',
            'showProductDetail'
        ]);
    }
 public function landing(Request $request)
{
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard.index');
        }
        if ($user->role === 'jastiper') {
            return redirect()->route('jastiper.dashboard.index');
        }
    }

    $kategoris = Kategori::all();
    $query = Barang::with('jastiper')->where('is_available', 'yes');

    // Filter Pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama_barang', 'like', '%' . $search . '%')
                ->orWhereHas('jastiper', function ($q2) use ($search) {
                    $q2->where('nama_toko', 'like', '%' . $search . '%');
                });
        });
    }

    // Filter Kategori
    if ($request->filled('kategori')) {
        $kategoriId = Kategori::where('nama', $request->kategori)->value('id');
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        } else {
            $query->whereRaw('1 = 0');
        }
    }

    // Sorting (Diubah menggunakan created_at)
    switch ($request->sort) {
        case 'harga_terendah':
            $query->orderBy('harga', 'asc');
            break;
        case 'harga_tertinggi':
            $query->orderBy('harga', 'desc');
            break;
        case 'terbaru':
            // PERUBAHAN DISINI: Menggunakan created_at
            $query->orderBy('created_at', 'desc');
            break;
        case 'nama_az':
            $query->orderBy('nama_barang', 'asc');
            break;
        default:
            // PERUBAHAN DISINI: Menggunakan created_at sebagai default
            $query->orderBy('created_at', 'desc');
    }

    $semuaProduk = $query->paginate(15);

    $produkBulanIni = [];
    if (!$request->hasAny(['search', 'kategori', 'sort'])) {
        $now = Carbon::now(); // Pastikan 'use Carbon\Carbon;' ada di atas file
        $produkBulanIni = Barang::with('jastiper')
            ->where('is_available', 'yes')
            // PERUBAHAN DISINI: Filter bulan ini menggunakan created_at
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
    }

    return view('user.landing_page', compact('produkBulanIni', 'semuaProduk', 'kategoris'));
}

    public function showProductDetail($id)
    {
        $barang = Barang::with('jastiper')->findOrFail($id);

        $jastiper = $barang->jastiper;

        $ulasanData = $jastiper
            ? Ulasan::where('jastiper_id', $jastiper->id)->count()
            : 0;

        if ($jastiper) {

            $jastiper->total_rating = $jastiper->rating ?? 0;
            $jastiper->total_penilaian = $ulasanData;
        }

        $produkSerupa = Barang::where('is_available', 'yes')
            ->where('id', '!=', $id)
            ->where('kategori_id', $barang->kategori_id)
            ->with('jastiper')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        if ($produkSerupa->isEmpty()) {
            $produkSerupa = Barang::where('is_available', 'yes')
                ->where('id', '!=', $id)
                ->with('jastiper')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        return view('user.detail_produk', compact('barang', 'produkSerupa', 'jastiper'));
    }

    // public function addToCart(Request $request, $barang_id)
    // {
    //     if (!Auth::check()) {
    //         return redirect()->route('login')->with('error', 'Anda harus login untuk menambahkan produk ke keranjang.');
    //     }

    //     $barang = Barang::findOrFail($barang_id);
    //     $qty = $request->input('qty', 1);
    //     if ($qty <= 0) {
    //         return back()->with('error', 'Kuantitas tidak valid.');
    //     }

    //     $cart = session()->get('cart', []);
    //     $userId = Auth::id();
    //     $cartKey = $userId . '-' . $barang_id;

    //     if (isset($cart[$cartKey])) {
    //         $cart[$cartKey]['qty'] += $qty;
    //     } else {
    //         $cart[$cartKey] = [
    //             "id" => $barang->id,
    //             "name" => $barang->nama_barang,
    //             "qty" => $qty,
    //             "price" => $barang->harga,
    //             "image" => $barang->foto_barang,
    //             "jastiper_toko" => $barang->jastiper->nama_toko ?? 'N/A'
    //         ];
    //     }

    //     session()->put('cart', $cart);

    //     return redirect()->route('produk.detail', $barang_id)
    //         ->with('success', $barang->nama_barang . ' berhasil ditambahkan ke keranjang!');
    // }

    // public function cartIndex()
    // {
    //     $userId = Auth::id();
    //     $cart = session()->get('cart', []);
    //     $userCart = [];

    //     foreach ($cart as $key => $item) {
    //         if (Str::startsWith($key, $userId . '-')) {
    //             $userCart[] = $item;
    //         }
    //     }

    //     return view('cart.index', compact('userCart'));
    // }

    public function loginForm()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $key = 'login:' . $request->ip() . '|' . strtolower($data['email']);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => 'Terlalu banyak percobaan! Coba lagi nanti.'])
                ->onlyInput('email');
        }

        $user = User::where('email', $data['email'])->first();

        if ($user && Hash::check($data['password'], $user->password)) {
            RateLimiter::clear($key);
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard.index'));
            }
            if ($user->role === 'jastiper') {
                return redirect()->intended(route('jastiper.dashboard.index'));
            }

            return redirect()->intended(route('home'));
        }

        RateLimiter::hit($key, 60);

        return back()->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }
    public function registerForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:users,username',
            'nama_lengkap' => 'nullable|string|max:150',
            'no_hp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $data['name'],
            'username' => $data['name'],
            'nama_lengkap' => $data['nama_lengkap'] ?? null,
            'no_hp' => $data['no_hp'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'pengguna',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        $previousUrl = url()->previous();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (Str::contains($previousUrl, ['/login', '/register', '/logout'])) {
            return redirect()->route('home');
        }
        return redirect()->route('home');
    }
}

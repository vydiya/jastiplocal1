<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Jastiper;
use App\Models\Rekening; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session; // <-- TAMBAH INI

class JastiperRegistrationController extends Controller
{
    /**
     * Tampilkan formulir pendaftaran Jastiper.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk mendaftar sebagai Jastiper.');
        }

        if (Auth::user()->jastiper) {
            return redirect()->route('home')->with('info', 'Anda sudah terdaftar sebagai Jastiper.');
        }
        
        return view('user.daftar-jastiper.index');
    }

    /**
     * Proses penyimpanan data pendaftaran Jastiper.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_toko' => 'required|string|max:100|unique:jastipers,nama_toko',
            'no_hp' => 'required|string|max:30|unique:jastipers,no_hp',
            'jangkauan' => 'required|string|max:255',
            'profile_toko_desc' => 'nullable|string', 
            'profile_toko' => 'nullable|image|max:2048', 
            
            // Validasi Rekening
            'tipe_rekening' => 'required|in:bank,e-wallet',
            'nama_penyedia' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:100',
            'nomor_akun' => ['required', 'string', 'max:50', Rule::unique('rekenings', 'nomor_akun')], 
        ]);

        DB::beginTransaction();

        try {
            $path = null;
            
            if ($request->hasFile('profile_toko')) {
                $path = $request->file('profile_toko')->store('jastiper_profiles', 'public');
            }

            $rekening = Rekening::create([
                'user_id' => $user->id, 
                'tipe_rekening' => $request->tipe_rekening,
                'nama_penyedia' => $request->nama_penyedia,
                'nama_pemilik' => $request->nama_pemilik,
                'nomor_akun' => $request->nomor_akun,
            ]);

            Jastiper::create([
                'user_id' => $user->id,
                'nama_toko' => $request->nama_toko,
                'no_hp' => $request->no_hp,
                'jangkauan' => $request->jangkauan,
                'rekening_id' => $rekening->id, 
                'profile_toko' => $path, 
            ]);
            $user->role = 'jastiper'; 
            $user->save();
            
            DB::commit();

            Auth::logout();
            Session::invalidate();
            Session::regenerateToken();
            
            return redirect()->route('login')->with('success', 
                'Pendaftaran Jastiper berhasil dikirim! Silakan login kembali.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($path) && $path) {
                Storage::disk('public')->delete($path);
            }
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}
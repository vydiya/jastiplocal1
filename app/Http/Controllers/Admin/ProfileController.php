<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Upload / update avatar sederhana
     */
    public function updateAvatar(Request $request)
    {
        // validasi sederhana
        $request->validate([
            'avatar' => 'required|image|max:2048' // max 2MB
        ]);

        $file = $request->file('avatar');

        // buat nama file unik
        $filename = 'avatar_'.time().'.'.$file->getClientOriginalExtension();

        // simpan ke storage/app/public/avatars
        $path = $file->storeAs('public/avatars', $filename);

        // buat public link: storage/avatars/...
        $publicUrl = Storage::url('avatars/'.$filename); // -> /storage/avatars/...

        // jika kamu sudah punya kolom avatar_url di users table, update user:
        if (Auth::check()) {
            $user = Auth::user();
            // contoh jika kamu punya kolom `avatar_url`:
            // $user->avatar_url = $publicUrl;
            // $user->save();

            // jika belum mau simpan DB, cukup flash pesan:
            session()->flash('status', 'Avatar diunggah (tidak disimpan ke DB).');
        }

        return back()->with('success', 'Avatar berhasil diunggah.');
    }
}

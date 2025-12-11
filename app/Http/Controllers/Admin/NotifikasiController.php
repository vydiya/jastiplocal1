<?php

namespace App\Http\Controllers\Admin; // Diubah dari App\Http\Controllers\Admin

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification; 
use App\Models\User; 
// use Carbon\Carbon; // Tidak diperlukan di controller ini, bisa dihapus

class NotifikasiController extends Controller
{
    /**
     * Menampilkan daftar notifikasi untuk pengguna yang sedang login.
     */
    public function index(Request $request)
    {
        $user = Auth::user(); 
        $search = $request->query('search');
        $status = $request->query('status', 'semua');

        $query = $user->notifications(); 

        // --- Logika Pencarian ---
        if ($search) {
            $query->where('data', 'like', "%{$search}%");
        }

        // --- Logika Filter Status ---
        if ($status === 'belum_baca') {
            $query->whereNull('read_at');
        } elseif ($status === 'sudah_baca') {
            $query->whereNotNull('read_at');
        }

        $notifikasis = $query->orderBy('created_at', 'desc')
                            ->paginate(15)
                            ->withQueryString();

        // Menggunakan unreadNotifications() untuk query count yang efisien
        $belum_baca_count = $user->unreadNotifications()->count(); 

        // Jika Anda memindahkan controller, pastikan path view ini benar:
        // Jika Anda menargetkan tampilan User/Jastiper, ganti 'admin.notifikasi.index'
        return view('admin.notifikasi.index', compact('notifikasis', 'search', 'status', 'belum_baca_count'));
    }

    // ---

    /**
     * Menandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        // Validasi kepemilikan notifikasi (Security check)
        if ($notification->notifiable_id != Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        
        $notification->markAsRead();
        return back()->with('success', 'Notifikasi berhasil ditandai sudah dibaca.');
    }

    // ---

    /**
     * Menandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllAsRead()
    {
        // Menandai semua notifikasi yang belum dibaca milik user ini sebagai sudah dibaca
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi berhasil ditandai sudah dibaca.');
    }
    
    // ---

    /**
     * Menghapus satu notifikasi.
     */
    public function destroy(DatabaseNotification $notification)
    {
        // Validasi kepemilikan notifikasi (Security check)
        if ($notification->notifiable_id != Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        
        $notification->delete();
        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
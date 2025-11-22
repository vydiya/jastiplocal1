<?php
namespace App\Traits;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Simpan log aktivitas sederhana.
     *
     * @param string $aksi
     * @param string|null $deskripsi
     * @param int|null $userId
     * @return \App\Models\LogAktivitas
     */
    protected function logActivity(string $aksi, ?string $deskripsi = null, ?int $userId = null)
    {
        $userId = $userId ?? optional(Auth::user())->id;

        return LogAktivitas::create([
            'user_id' => $userId ?: 0,
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'waktu' => now(),
        ]);
    }
}

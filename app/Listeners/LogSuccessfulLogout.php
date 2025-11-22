<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\LogAktivitas;

class LogSuccessfulLogout
{
    public function handle(Logout $event)
    {
        $user = $event->user;
        LogAktivitas::create([
            'user_id' => $user?->id ?? 0,
            'aksi' => 'logout',
            'deskripsi' => 'User logged out',
            'waktu' => now(),
        ]);
    }
}

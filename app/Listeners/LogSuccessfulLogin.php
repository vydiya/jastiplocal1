<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\LogAktivitas;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;
        LogAktivitas::create([
            'user_id' => $user->id,
            'aksi' => 'login',
            'deskripsi' => 'User logged in',
            'waktu' => now(),
        ]);
    }
}

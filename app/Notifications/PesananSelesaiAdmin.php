<?php

namespace App\Notifications;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PesananSelesaiAdmin extends Notification
{
    use Queueable;
    protected $pesanan;

    public function __construct(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'jenis_notifikasi' => 'Pesanan Selesai',
            'pesan' => "User **{$this->pesanan->user->name}** telah menyelesaikan Pesanan ID **#{$this->pesanan->id}**. Silakan cek status pencairan dana.",
            'pesanan_id' => $this->pesanan->id,
        ];
    }
}
<?php

namespace App\Notifications;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranDitolak extends Notification
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
            'jenis_notifikasi' => 'Pembayaran Ditolak',
            'pesan' => "Pembayaran Anda untuk pesanan ID **#{$this->pesanan->id}** telah **DITOLAK** (Nominal tidak sesuai/Bukti tidak valid). Pesanan Anda kini berstatus **DIBATALKAN**.",
            'pesanan_id' => $this->pesanan->id,
        ];
    }
}
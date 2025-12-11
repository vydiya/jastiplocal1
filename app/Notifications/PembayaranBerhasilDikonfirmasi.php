<?php

namespace App\Notifications;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranBerhasilDikonfirmasi extends Notification
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
            'jenis_notifikasi' => 'Konfirmasi Pembayaran',
            'pesan' => "Pembayaran Anda untuk pesanan ID **#{$this->pesanan->id}** telah **DIKONFIRMASI** oleh Admin. Pesanan Anda kini berstatus **DIPROSES**.",
            'pesanan_id' => $this->pesanan->id,
        ];
    }
}
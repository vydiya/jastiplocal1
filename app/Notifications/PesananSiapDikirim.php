<?php

namespace App\Notifications;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PesananSiapDikirim extends Notification
{
    use Queueable;

    protected $pesanan;

    public function __construct(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan ke database
    }

    public function toDatabase($notifiable)
    {
        return [
            'jenis_notifikasi' => 'Update Status Pesanan',
            'pesan' => "Hore! Pesanan ID **#{$this->pesanan->id}** sedngan **DIKIRIM**. Jastiper akan segera mengirimkan paket ke alamat Anda.",
            'pesanan_id' => $this->pesanan->id,
        ];
    }
}
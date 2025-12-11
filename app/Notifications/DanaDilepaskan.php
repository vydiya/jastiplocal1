<?php

namespace App\Notifications;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
// use Illuminate\Notifications\Messages\DatabaseMessage; // Import ini tidak diperlukan jika hanya menggunakan array return

class DanaDilepaskan extends Notification
{
    use Queueable;

    protected $pesanan;
    protected $jumlahBersih;
    protected $biayaAdmin;

    public function __construct(Pesanan $pesanan, $jumlahBersih, $biayaAdmin)
    {
        $this->pesanan = $pesanan;
        $this->jumlahBersih = $jumlahBersih;
        $this->biayaAdmin = $biayaAdmin;
    }

    /**
     * Dapatkan saluran notifikasi.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Dapatkan representasi notifikasi sebagai array database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        // Format angka untuk ditampilkan di pesan
        $jumlahFormat = number_format($this->jumlahBersih, 0, ',', '.');
        $adminFormat = number_format($this->biayaAdmin, 0, ',', '.');
        
        // Data yang akan disimpan ke kolom 'data' di tabel 'notifications'
        return [
            // Kolom kustom yang Anda definisikan:
            'jenis_notifikasi' => 'Pelepasan Dana',
            'pesan' => "Dana sebesar **Rp $jumlahFormat** (Pesanan ID #{$this->pesanan->id}) telah ditransfer ke rekeningmu setelah dipotong biaya admin (Rp $adminFormat).",
            
            // Data tambahan untuk kebutuhan navigasi/proses:
            'pesanan_id' => $this->pesanan->id,
            'jumlah_bersih' => $this->jumlahBersih,
            'biaya_admin' => $this->biayaAdmin,
        ];
    }
}
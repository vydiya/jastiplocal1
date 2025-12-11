<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesanan;
use App\Models\Barang;
use App\Models\DetailPesanan;

class DetailPesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pesanans = Pesanan::all();
        $barangs = Barang::all();

        if ($pesanans->isEmpty() || $barangs->isEmpty()) {
            $this->command->warn("Seeder DetailPesanan: tidak ada pesanan atau barang untuk diisi.");
            return;
        }

       foreach ($pesanans as $pesanan) {

    // Jika pesanan sudah punya detail, skip
    if ($pesanan->detail_pesanans()->exists()) {
        // Tampilkan pesan di console artisan
        $this->command->info("Pesanan ID {$pesanan->id} sudah punya detail, dilewati...");
        continue;
    }

    // Pilih 1–5 barang random
    $jumlahBarang = rand(1, min(5, $barangs->count()));
    $selectedBarangs = $barangs->random($jumlahBarang);

    foreach ($selectedBarangs as $barang) {
        $jumlah = rand(1, 3);
        $subtotal = $barang->harga * $jumlah;

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'barang_id'  => $barang->id,
            'jumlah'     => $jumlah,
            'subtotal'   => $subtotal,
        ]);
    }

    // Update total harga
    $pesanan->update([
        'total_harga' => $pesanan->detail_pesanans()->sum('subtotal')
    ]);
}

    }
}

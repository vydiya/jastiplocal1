<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ulasan;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Support\Str;

class UlasanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pesanans = Pesanan::with('user', 'jastiper')->get();

        if ($pesanans->isEmpty()) {
            $this->command->warn("Seeder Ulasan: tidak ada pesanan untuk diisi ulasan.");
            return;
        }

        foreach ($pesanans as $pesanan) {
            // Buat 1 ulasan per pesanan dengan probabilitas 70%
            if (rand(1, 100) <= 70 && $pesanan->jastiper) {
                Ulasan::create([
                    'pesanan_id'    => $pesanan->id,
                    'user_id'       => $pesanan->user_id,
                    'jastiper_id'   => $pesanan->jastiper->id,
                    'rating'        => rand(1, 5),
                    'komentar'      => Str::random(20) . ' komentar contoh',
                    'tanggal_ulasan'=> now()->subDays(rand(0, 10)),
                ]);
            }
        }
    }
}

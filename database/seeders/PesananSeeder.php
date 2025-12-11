<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesanan;
use App\Models\AlurDana; 
use App\Models\User;
use App\Models\Jastiper;
use Illuminate\Support\Str;

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan model-model ini tersedia dan memiliki data
        // Asumsi: Anda sudah memiliki seeder untuk User (pengguna, admin) dan Jastiper
        $users = User::where('role', 'pengguna')->get(); 
        $admins = User::where('role', 'admin')->get(); 
        $jastipers = Jastiper::all();

        if ($users->isEmpty() || $jastipers->isEmpty() || $admins->isEmpty()) {
            $this->command->warn("Seeder Pesanan: tidak cukup user (pengguna/admin) atau jastiper untuk diisi. Pastikan UserSeeder sudah dijalankan.");
            return;
        }

        $adminKonfirmator = $admins->random();

        // Daftar status ENUM
        $allStatuses = [
            'MENUNGGU_PEMBAYARAN',         // Index 0: Belum bayar
            'MENUNGGU_KONFIRMASI_ADMIN',   // Index 1: Sudah bayar, menunggu konfirmasi
            'DIPROSES',                    // Index 2: Sudah dikonfirmasi, sedang diproses
            'SIAP_DIKIRIM',                // Index 3: Sudah diproses, siap kirim
            'SELESAI',                     // Index 4: Selesai
            'DIBATALKAN'                   // Index 5: Dibatalkan
        ];

        // Biaya Admin (misalnya 5% dari total harga)
        $persentaseBiayaAdmin = 0.05; 

        // Untuk setiap user, buat beberapa pesanan dengan skenario yang berbeda
        foreach ($users as $user) {
            
            // Buat 6 pesanan untuk menampilkan semua skenario status
            $scenarios = $allStatuses; 

            foreach ($scenarios as $statusPesanan) {
                $jastiper = $jastipers->random();
                $totalHarga = rand(100000, 1000000); // Harga lebih bervariasi
                
                // Tentukan status dana yang logis berdasarkan status pesanan
                $statusDana = 'TERTAHAN';
                if ($statusPesanan === 'SELESAI') {
                    // 50% DILEPASKAN, 50% MENUNGGU_PELEPASAN
                    $statusDana = ['MENUNGGU_PELEPASAN', 'DILEPASKAN'][rand(0, 1)]; 
                } elseif ($statusPesanan === 'DIPROSES' || $statusPesanan === 'SIAP_DIKIRIM') {
                    $statusDana = 'TERTAHAN';
                }

                // Tentukan apakah pesanan dibatalkan (untuk menguji soft delete)
                $isCancelled = $statusPesanan === 'DIBATALKAN';

                // Tentukan kapan pesanan dibuat
                $tanggalPesan = now()->subDays(rand(1, 60));

                // 1. BUAT PESANAN
                $pesanan = Pesanan::create([
                    'user_id'              => $user->id,
                    'jastiper_id'          => $jastiper->id,
                    'tanggal_pesan'        => $tanggalPesan,
                    'total_harga'          => $totalHarga,
                    'status_pesanan'       => $statusPesanan,
                    'status_dana_jastiper' => $statusDana, 
                    'alamat_pengiriman'    => $user->alamat ?? 'Jl. Mockup No. ' . rand(1,100) . ', Kota Contoh',
                    'no_hp'                => $user->no_hp ?? '081' . rand(100000000,999999999),
                    'deleted_at'           => $isCancelled ? now() : null, // Soft Delete jika DIBATALKAN
                ]);

                // 2. BUAT ENTRY ALUR DANA (PEMBAYARAN USER)
                // Hanya buat jika status sudah mencapai "MENUNGGU_KONFIRMASI_ADMIN" atau lebih
                if (array_search($statusPesanan, $allStatuses) >= 1 && $statusPesanan !== 'DIBATALKAN') { 
                    
                    $konfirmasiStatus = 'DIKONFIRMASI';
                    if ($statusPesanan === 'MENUNGGU_KONFIRMASI_ADMIN') {
                        // Untuk skenario ini, buat 50% DIKONFIRMASI (seharusnya tidak terjadi, tapi untuk alur data),
                        // dan 50% MENUNGGU_CEK
                        $konfirmasiStatus = ['DIKONFIRMASI', 'MENUNGGU_CEK'][rand(0, 1)];
                    }
                    
                    AlurDana::create([
                        'pesanan_id'        => $pesanan->id,
                        'jenis_transaksi'   => 'PEMBAYARAN_USER',
                        'jumlah_dana'       => $totalHarga,
                        'biaya_admin'       => 0.00, // Biaya admin 0 di sisi user
                        'bukti_tf_path'     => 'bukti/tf_user_' . $pesanan->id . '.jpg',
                        'status_konfirmasi' => $konfirmasiStatus, 
                        'konfirmator_id'    => $konfirmasiStatus === 'DIKONFIRMASI' ? $adminKonfirmator->id : null,
                        'tanggal_transfer'  => $tanggalPesan->addHours(rand(1, 24)),
                        'created_at'        => $tanggalPesan->addHours(rand(25, 48)),
                        'updated_at'        => $tanggalPesan->addHours(rand(25, 48)),
                    ]);
                }
                
                // 3. BUAT ENTRY ALUR DANA (PELEPASAN DANA OLEH ADMIN)
                // Hanya buat jika status pesanan SELESAI dan dana sudah DILEPASKAN
                if ($statusPesanan === 'SELESAI' && $statusDana === 'DILEPASKAN') {
                    // Pastikan pembayaran user sudah ada (untuk mencegah error FK)
                    if ($pesanan->alurDana()->where('jenis_transaksi', 'PEMBAYARAN_USER')->exists()) {
                        
                        $biayaAdmin = round($totalHarga * $persentaseBiayaAdmin, 2); 
                        $jumlahPelepasanDana = $totalHarga - $biayaAdmin;

                        AlurDana::create([
                            'pesanan_id'        => $pesanan->id,
                            'jenis_transaksi'   => 'PELEPASAN_DANA',
                            'jumlah_dana'       => $jumlahPelepasanDana, 
                            'biaya_admin'       => $biayaAdmin, 
                            'bukti_tf_path'     => 'bukti/tf_admin_' . $pesanan->id . '.jpg',
                            'status_konfirmasi' => 'DIKONFIRMASI',
                            'konfirmator_id'    => $adminKonfirmator->id,
                            'tanggal_transfer'  => now()->subDays(rand(1, 5)),
                            'created_at'        => now()->subDays(rand(1, 5))->addHours(1),
                            'updated_at'        => now()->subDays(rand(1, 5))->addHours(1),
                        ]);
                    }
                }
            }
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rekening;
use App\Models\User;
use App\Models\Jastiper;
use Illuminate\Support\Str;

class RekeningSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $jastipers = Jastiper::all();

        if ($users->isEmpty() || $jastipers->isEmpty()) {
            $this->command->warn("Seeder Rekening: Tidak cukup data User atau Jastiper untuk diisi.");
            return;
        }

        $bankProviders = [
            'BCA' => 'bank', 
            'Mandiri' => 'bank', 
            'BNI' => 'bank', 
            'BRI' => 'bank', 
            'Permata' => 'bank', 
            'DANA' => 'e-wallet', 
            'GoPay' => 'e-wallet' 
        ];
        
        $jastiperUsers = $users->whereIn('id', $jastipers->pluck('user_id'));
        $generalUsers = $users->whereNotIn('id', $jastipers->pluck('user_id'));

        foreach ($jastipers as $jastiper) {
            $user = $jastiperUsers->where('id', $jastiper->user_id)->first();
            if (!$user) continue;

            $provider = array_rand($bankProviders);
            $tipe = $bankProviders[$provider]; 

            $rekening = Rekening::create([
                'user_id' => $user->id,
                'tipe_rekening' => $tipe, 
                'nama_penyedia' => $provider,
                'nama_pemilik' => $user->name,
                'nomor_akun' => rand(1000000000, 9999999999), 
                'status_aktif' => 'aktif', 
                'tanggal_input' => now()->subDays(rand(1, 100)),
            ]);

            // Hubungkan Rekening ke Jastiper
            $jastiper->rekening_id = $rekening->id;
            $jastiper->save();
        }


        // --- 2. SEED REKENING UNTUK PENGGUNA UMUM (OPSIONAL) ---
        // Buat rekening untuk sekitar 50% dari pengguna umum
        $generalUsers->shuffle()->take(floor($generalUsers->count() / 2))->each(function ($user) use ($bankProviders) {
            
            $provider = array_rand($bankProviders);
            $tipe = $bankProviders[$provider];
            
            // Randomly select 'aktif' or 'nonaktif'
            $statusAktif = ['aktif', 'nonaktif'][rand(0, 1)];

            Rekening::create([
                'user_id' => $user->id,
                'tipe_rekening' => $tipe, 
                'nama_penyedia' => $provider,
                'nama_pemilik' => $user->name,
                'nomor_akun' => rand(1000000000, 9999999999),
                'status_aktif' => $statusAktif, 
                'tanggal_input' => now()->subDays(rand(1, 100)),
            ]);
        });
    }
}
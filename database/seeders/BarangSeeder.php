<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Jastiper;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $jastipers = Jastiper::all();
        $kategoris = Kategori::all();

        if ($jastipers->isEmpty()) {
            $this->command->warn("Seeder Barang: tidak ada jastiper untuk diisi.");
            return;
        }

        // Ambil semua file dummy dari storage/app/public/barang/
        $dummyPath = storage_path('app/public/barang');
        $dummyFiles = File::files($dummyPath);

        if (empty($dummyFiles)) {
            $this->command->warn("Seeder Barang: folder storage/app/public/barang kosong.");
            return;
        }

        foreach ($jastipers as $jastiper) {
            $jumlahBarang = rand(3, 5); // 3-5 barang per jastiper

            for ($i = 0; $i < $jumlahBarang; $i++) {
                // Pilih kategori random atau null
                $kategori = $kategoris->isNotEmpty() && rand(0,1) ? $kategoris->random() : null;

                // Pilih foto dummy random
                $fotoFile = $dummyFiles[array_rand($dummyFiles)];
                $fileName = basename($fotoFile);

                // Salin ke storage/app/public/barangs (folder utama foto barang)
                Storage::disk('public')->putFileAs('barangs', $fotoFile, $fileName);

                Barang::create([
                    'jastiper_id'     => $jastiper->id,
                    'kategori_id'     => $kategori?->id,
                    'admin_id'        => null,
                    'nama_barang'     => 'Barang ' . Str::random(5),
                    'deskripsi'       => 'Deskripsi contoh ' . Str::random(5),
                    'harga'           => rand(10000, 500000),
                    'stok'            => rand(1, 50),
                    'is_available'    => ['yes','no'][rand(0,1)],
                    'foto_barang'     => 'barangs/' . $fileName,
                    // 'status_validasi' => 'pending',
                    'tanggal_input'   => now()->subDays(rand(0, 10)),
                ]);
            }
        }
    }
}

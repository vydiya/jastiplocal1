<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use Carbon\Carbon;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategoris = [
            // Kategori umum untuk barang jastip
            ['nama' => 'Elektronik', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Pakaian (Fashion)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Makanan & Minuman', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Kesehatan & Kecantikan', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Otomotif', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Mainan & Hobi', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Perlengkapan Rumah', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Lain-lain', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        // Gunakan insert alih-alih create untuk efisiensi jika data tidak perlu dicek
        Kategori::insert($kategoris);

        $this->command->info('Kategori berhasil diisi!');
    }
}
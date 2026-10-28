<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        if (Layanan::exists()) {
            return;
        }

        Layanan::insert([
            [
                'nama_ekspedisi' => 'JNE',
                'jenis_layanan' => 'Reguler',
                'zonasi' => 'Zona 1',
                'tarif' => 9000,
                'estimasi' => '2-3 Hari',
                'status' => 'ON',
            ],
            [
                'nama_ekspedisi' => 'SICEPAT',
                'jenis_layanan' => 'Express',
                'zonasi' => 'Zona 3',
                'tarif' => 25000,
                'estimasi' => '3-5 Hari',
                'status' => 'ON',
            ],
        ]);
    }
}

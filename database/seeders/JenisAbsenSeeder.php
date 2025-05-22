<?php

namespace Database\Seeders;

use App\Models\JenisAbsen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisAbsenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis_absen = ['Masuk Kerja', 'Pulang Kerja'];
        foreach ($jenis_absen as $jenis) {
            JenisAbsen::create(['name' => $jenis]);
        }
    }
}

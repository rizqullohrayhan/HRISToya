<?php

namespace Database\Seeders;

use App\Models\TipeAktivitas as ModelsTipeAktivitas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipeAktivitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_tipe = [
            'PENAWARAN-BIASA',
            'PENAWARAN-BIASA-PROSES',
            'PENAWARAN-TENDER',
            'PENAWARAN-TENDER-PROSES',
            'MONITORING MAINTENANCE',
            'MARKETING INTELLIGENCE',
            'PURCHASE ORDER',
            'OTHERS',
        ];

        foreach ($list_tipe as $tipe) {
            ModelsTipeAktivitas::create(['name' => $tipe]);
        }
    }
}

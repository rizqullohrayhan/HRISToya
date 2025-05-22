<?php

namespace Database\Seeders;

use App\Models\CaraAktivitas as ModelsCaraAktivitas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CaraAktivitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_cara_aktivitas = [
            'VISIT',
            'TELP',
            'FAX',
            'EMAIL',
            'BBM',
            'SMS',
            'OFFICE',
            'WA',
            'OTHERS',
        ];

        foreach ($list_cara_aktivitas as $cara) {
            ModelsCaraAktivitas::create(['name' => $cara]);
        }
    }
}

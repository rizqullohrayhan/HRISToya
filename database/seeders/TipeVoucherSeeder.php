<?php

namespace Database\Seeders;

use App\Models\TipeVoucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipeVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list = ['Bukti Bank'];
        foreach ($list as $name) {
            TipeVoucher::create(['name' => $name]);
        }
    }
}

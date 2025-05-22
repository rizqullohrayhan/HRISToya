<?php

namespace Database\Seeders;

use App\Models\StatusVoucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list = ['Open', 'Close'];
        foreach ($list as $name) {
            StatusVoucher::create(['name' => $name]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $team_list = [
            'PT Toya Indo Manunggal',
            'Logistik',
            'Finance',
            'Marketing',
            'Warehouse',
        ];

        foreach ($team_list as $team) {
            Team::create(['name' => $team]);
        }
    }
}

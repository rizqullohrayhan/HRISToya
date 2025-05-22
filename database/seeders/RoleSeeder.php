<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_list = [
            'ADM',
            'SPV',
            'OPR',
        ];

        foreach ($role_list as $role) {
            Role::create(['name' => $role]);
        }
    }
}

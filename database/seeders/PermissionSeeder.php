<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission_list = [
            'add role',
            'view role',
            'edit role',
            'delete role',
            'add permission',
            'view permission',
            'edit permission',
            'delete permission',
            'add user',
            'view user',
            'edit user',
            'delete user',
            'add team',
            'view team',
            'edit team',
            'delete team',
            'add rekanan',
            'view rekanan',
            'edit rekanan',
            'delete rekanan',
            'add absen',
            'view absen',
            'edit absen',
            'delete absen',
            'view other absen',
            'add laporan aktifitas',
            'view laporan aktifitas',
            'edit laporan aktifitas',
            'delete laporan aktifitas',
            'view other aktifitas',
        ];

        foreach ($permission_list as $value) {
            Permission::create(['name' => $value]);
        }
    }
}

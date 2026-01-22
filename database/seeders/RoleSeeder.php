<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. 重置缓存
        Artisan::call('permission:cache-reset');

        // 2. 生成权限
        Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
            '--option' => 'policies_and_permissions',
            '--ignore-existing-policies' => true,
        ]);
    }
}

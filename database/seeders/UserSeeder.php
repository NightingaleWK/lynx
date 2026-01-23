<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. 创建超级管理员
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => '楷楷',
                'password' => Hash::make('Admin@123'),
            ]
        );

        // 分配超级管理员角色
        Artisan::call('shield:super-admin', [
            '--user' => 1,
            '--panel' => 'admin',
        ]);

        // 2. 创建 Partner 用户
        $partners = [
            ['name' => '敏敏', 'email' => 'minmin@admin.com'],
        ];

        foreach ($partners as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('Admin@123'),
                ]
            );

            $user->assignRole('partner');
        }
    }
}

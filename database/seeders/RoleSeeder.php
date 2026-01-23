<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. 重置缓存
        Artisan::call('permission:cache-reset');

        // 2. 生成基础权限
        Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
            '--option' => 'policies_and_permissions',
            '--ignore-existing-policies' => true,
        ]);

        // 3. 定义并创建角色
        $roles = [
            ['name' => 'partner', 'guard_name' => 'web'],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']]
            );
        }

        // 4. 为角色分配权限
        $partnerRole = Role::where('name', 'partner')->first();
        if ($partnerRole) {
            $partnerRole->givePermissionTo([
                // 页面访问权限
                'OrderNow',

                // 订单相关权限 (仅查看和创建，不可删除)
                'ViewAny:Order',
                'View:Order',
                'Create:Order',
                'Update:Order',
            ]);
        }
    }
}

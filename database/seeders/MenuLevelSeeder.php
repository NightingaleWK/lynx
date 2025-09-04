<?php

namespace Database\Seeders;

use App\Models\MenuLevel;
use Illuminate\Database\Seeder;

class MenuLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 创建菜谱分类
        $menuLevels = [
            [
                'name' => '汤类',
                'sort_order' => 100,
                'is_visible' => true,
            ],
            [
                'name' => '炒菜',
                'sort_order' => 90,
                'is_visible' => true,
            ],
            [
                'name' => '凉菜',
                'sort_order' => 80,
                'is_visible' => true,
            ],
        ];

        foreach ($menuLevels as $level) {
            MenuLevel::create($level);
        }

        $this->command->info('已创建 ' . MenuLevel::count() . ' 个菜谱分类');
    }
}

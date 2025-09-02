<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => '默认',
                'sort_order' => 1,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '炒菜',
                'sort_order' => 2,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '煲汤',
                'sort_order' => 3,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '凉菜',
                'sort_order' => 4,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '甜品',
                'sort_order' => 5,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '面食',
                'sort_order' => 6,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '主食',
                'sort_order' => 7,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '饮品',
                'sort_order' => 8,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '小吃',
                'sort_order' => 9,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // 清空现有数据（可选）
        DB::table('menu_levels')->delete();

        // 插入预设分类数据
        DB::table('menu_levels')->insert($categories);

        $this->command->info('已创建 ' . count($categories) . ' 个菜谱分类');
    }
}

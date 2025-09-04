<?php

namespace Database\Seeders;

use App\Models\MaterialLevel;
use Illuminate\Database\Seeder;

class MaterialLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 根据菜谱需要的分类创建数据
        $materialLevels = [
            [
                'name' => '蔬菜',
                'sort_order' => 100,
                'is_visible' => true,
            ],
            [
                'name' => '调料',
                'sort_order' => 90,
                'is_visible' => true,
            ],
            [
                'name' => '蛋类',
                'sort_order' => 80,
                'is_visible' => true,
            ],
            [
                'name' => '豆制品',
                'sort_order' => 70,
                'is_visible' => true,
            ],
        ];

        foreach ($materialLevels as $level) {
            MaterialLevel::create($level);
        }

        $this->command->info('已创建 ' . MaterialLevel::count() . ' 个物料分类');
    }
}

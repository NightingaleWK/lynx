<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 根据菜谱需要的单位创建数据
        $units = [
            [
                'name' => '个',
                'en_name' => 'piece',
                'remark' => '用于计数，如土豆、鸡蛋等',
            ],
            [
                'name' => '瓣',
                'en_name' => 'clove',
                'remark' => '用于大蒜、蒜瓣等',
            ],
            [
                'name' => '根',
                'en_name' => 'root',
                'remark' => '用于黄瓜、萝卜等长条状蔬菜',
            ],
            [
                'name' => '张',
                'en_name' => 'sheet',
                'remark' => '用于豆腐皮、千张等片状豆制品',
            ],
            [
                'name' => '茶勺',
                'en_name' => 'teaspoon',
                'remark' => '小茶匙，用于调味料',
            ],
            [
                'name' => '勺',
                'en_name' => 'spoon',
                'remark' => '普通汤勺，用于液体调味料',
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }

        $this->command->info('已创建 ' . Unit::count() . ' 个单位');
    }
}

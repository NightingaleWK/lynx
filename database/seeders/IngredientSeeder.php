<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\IngredientAisle;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $aisles = IngredientAisle::pluck('id', 'name');

        $ingredients = [
            // 蔬菜
            ['name' => '西红柿', 'base_unit' => 'g', 'aisle' => '新鲜蔬菜'],
            ['name' => '土豆', 'base_unit' => 'g', 'aisle' => '新鲜蔬菜'],
            ['name' => '青椒', 'base_unit' => 'g', 'aisle' => '新鲜蔬菜'],
            ['name' => '黄瓜', 'base_unit' => 'g', 'aisle' => '新鲜蔬菜'],
            ['name' => '大葱', 'base_unit' => 'g', 'aisle' => '新鲜蔬菜'],
            ['name' => '生姜', 'base_unit' => 'g', 'aisle' => '新鲜蔬菜'],
            ['name' => '大蒜', 'base_unit' => 'pc', 'aisle' => '新鲜蔬菜'], // 瓣/头
            ['name' => '茄子', 'base_unit' => 'g', 'aisle' => '新鲜蔬菜'],
            ['name' => '上海青', 'base_unit' => 'g', 'aisle' => '新鲜蔬菜'],

            // 肉禽蛋
            ['name' => '鸡蛋', 'base_unit' => 'pc', 'aisle' => '肉禽蛋品'],
            ['name' => '猪五花肉', 'base_unit' => 'g', 'aisle' => '肉禽蛋品'],
            ['name' => '猪里脊', 'base_unit' => 'g', 'aisle' => '肉禽蛋品'],
            ['name' => '鸡胸肉', 'base_unit' => 'g', 'aisle' => '肉禽蛋品'],
            ['name' => '牛腩', 'base_unit' => 'g', 'aisle' => '肉禽蛋品'],

            // 海鲜
            ['name' => '基围虾', 'base_unit' => 'g', 'aisle' => '海鲜水产'],

            // 调味
            ['name' => '食用油', 'base_unit' => 'ml', 'aisle' => '粮油调味'],
            ['name' => '盐', 'base_unit' => 'g', 'aisle' => '粮油调味'],
            ['name' => '生抽', 'base_unit' => 'ml', 'aisle' => '粮油调味'],
            ['name' => '老抽', 'base_unit' => 'ml', 'aisle' => '粮油调味'],
            ['name' => '白糖', 'base_unit' => 'g', 'aisle' => '粮油调味'],
            ['name' => '淀粉', 'base_unit' => 'g', 'aisle' => '粮油调味'],
            ['name' => '料酒', 'base_unit' => 'ml', 'aisle' => '粮油调味'],
            ['name' => '香油', 'base_unit' => 'ml', 'aisle' => '粮油调味'],
            ['name' => '米醋', 'base_unit' => 'ml', 'aisle' => '粮油调味'],

            // 主食 ingredient? 
            ['name' => '大米', 'base_unit' => 'g', 'aisle' => '粮油调味'],
            ['name' => '面条', 'base_unit' => 'g', 'aisle' => '粮油调味'],
        ];

        foreach ($ingredients as $data) {
            $aisleName = $data['aisle'];
            if (!isset($aisles[$aisleName])) continue;

            Ingredient::firstOrCreate(
                ['name' => $data['name']],
                [
                    'base_unit' => $data['base_unit'],
                    'aisle_id' => $aisles[$aisleName]
                ]
            );
        }
    }
}

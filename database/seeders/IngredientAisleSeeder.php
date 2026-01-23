<?php

namespace Database\Seeders;

use App\Models\IngredientAisle;
use Illuminate\Database\Seeder;

class IngredientAisleSeeder extends Seeder
{
    public function run(): void
    {
        $aisles = [
            ['name' => '新鲜蔬菜', 'sort_order' => 1],
            ['name' => '时令水果', 'sort_order' => 2],
            ['name' => '肉禽蛋品', 'sort_order' => 3],
            ['name' => '海鲜水产', 'sort_order' => 4],
            ['name' => '粮油调味', 'sort_order' => 5],
            ['name' => '干货杂粮', 'sort_order' => 6],
            ['name' => '冷冻食品', 'sort_order' => 7],
            ['name' => '奶制品', 'sort_order' => 8],
        ];

        foreach ($aisles as $aisle) {
            IngredientAisle::firstOrCreate(['name' => $aisle['name']], $aisle);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');

        // Helper to get ingredient ID
        $ing = fn($name) => Ingredient::where('name', $name)->first()?->id;

        $dishes = [
            [
                'name' => '西红柿炒鸡蛋',
                'category' => '下饭热菜',
                'description' => '国民第一菜，酸甜开胃，老少皆宜。',
                'ingredients' => [
                    ['name' => '西红柿', 'qty' => 300, 'unit' => 'g', 'remark' => '2个'],
                    ['name' => '鸡蛋', 'qty' => 3, 'unit' => 'pc', 'remark' => '3个'],
                    ['name' => '食用油', 'qty' => 30, 'unit' => 'ml', 'remark' => '适量'],
                    ['name' => '盐', 'qty' => 3, 'unit' => 'g', 'remark' => '少许'],
                    ['name' => '大葱', 'qty' => 10, 'unit' => 'g', 'remark' => '葱花'],
                    ['name' => '白糖', 'qty' => 5, 'unit' => 'g', 'remark' => '提鲜'],
                ]
            ],
            [
                'name' => '青椒土豆丝',
                'category' => '下饭热菜',
                'description' => '清脆爽口，家常必备。',
                'ingredients' => [
                    ['name' => '土豆', 'qty' => 400, 'unit' => 'g', 'remark' => '2个'],
                    ['name' => '青椒', 'qty' => 50, 'unit' => 'g', 'remark' => '1个'],
                    ['name' => '大蒜', 'qty' => 2, 'unit' => 'pc', 'remark' => '2瓣'],
                    ['name' => '米醋', 'qty' => 15, 'unit' => 'ml', 'remark' => '1勺'],
                    ['name' => '盐', 'qty' => 3, 'unit' => 'g', 'remark' => '适量'],
                ]
            ],
            [
                'name' => '红烧肉',
                'category' => '下饭热菜',
                'description' => '肥而不腻，入口即化，需要耐心慢炖。',
                'ingredients' => [
                    ['name' => '猪五花肉', 'qty' => 500, 'unit' => 'g', 'remark' => '1斤'],
                    ['name' => '生姜', 'qty' => 10, 'unit' => 'g', 'remark' => '3片'],
                    ['name' => '大葱', 'qty' => 20, 'unit' => 'g', 'remark' => '1段'],
                    ['name' => '生抽', 'qty' => 15, 'unit' => 'ml', 'remark' => '1勺'],
                    ['name' => '老抽', 'qty' => 10, 'unit' => 'ml', 'remark' => '半勺'],
                    ['name' => '料酒', 'qty' => 30, 'unit' => 'ml', 'remark' => '2勺'],
                    ['name' => '冰糖', 'qty' => 20, 'unit' => 'g', 'remark' => '一把'], // 假设有冰糖，或者用白糖代替
                ]
            ],
            [
                'name' => '拍黄瓜',
                'category' => '开胃凉菜',
                'description' => '夏日清凉，简单快手。',
                'ingredients' => [
                    ['name' => '黄瓜', 'qty' => 300, 'unit' => 'g', 'remark' => '2根'],
                    ['name' => '大蒜', 'qty' => 5, 'unit' => 'pc', 'remark' => '5瓣'],
                    ['name' => '香油', 'qty' => 5, 'unit' => 'ml', 'remark' => '少许'],
                    ['name' => '米醋', 'qty' => 20, 'unit' => 'ml', 'remark' => '2勺'],
                    ['name' => '盐', 'qty' => 3, 'unit' => 'g', 'remark' => '适量'],
                ]
            ],
            [
                'name' => '紫菜蛋花汤',
                'category' => '滋补汤羹',
                'description' => '快手汤，鲜美解腻。',
                'ingredients' => [
                    ['name' => '鸡蛋', 'qty' => 1, 'unit' => 'pc', 'remark' => '1个'],
                    ['name' => '香油', 'qty' => 3, 'unit' => 'ml', 'remark' => '几滴'],
                    ['name' => '盐', 'qty' => 2, 'unit' => 'g', 'remark' => '少许'],
                    // 紫菜缺省，暂不添加，或者在IngredientSeeder加一下
                ]
            ],
        ];

        // Handle "Ice Sugar" missing in IngredientSeeder, fallback to sugar
        $sugarId = $ing('白糖');

        foreach ($dishes as $data) {
            $catName = $data['category'];
            if (!isset($categories[$catName])) continue;

            $dish = Dish::firstOrCreate(
                ['name' => $data['name']],
                [
                    'category_id' => $categories[$catName],
                    'description' => $data['description'],
                    'frequency' => rand(0, 20),
                ]
            );

            // Sync Ingredients
            $syncData = [];
            foreach ($data['ingredients'] as $item) {
                $ingId = $ing($item['name']);

                // Fallback logic
                if (!$ingId && $item['name'] === '冰糖') $ingId = $sugarId;

                if ($ingId) {
                    $syncData[$ingId] = [
                        'quantity' => $item['qty'],
                        'unit' => $item['unit'],
                        'remark' => $item['remark'],
                    ];
                }
            }

            if (!empty($syncData)) {
                $dish->ingredients()->sync($syncData);
            }
        }
    }
}

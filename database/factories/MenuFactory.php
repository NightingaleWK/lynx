<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\MenuLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement([
                '红烧肉',
                '宫保鸡丁',
                '麻婆豆腐',
                '糖醋里脊',
                '鱼香肉丝',
                '回锅肉',
                '水煮鱼',
                '酸菜鱼',
                '口水鸡',
                '白切鸡',
                '蒜蓉西兰花',
                '清炒小白菜',
                '西红柿鸡蛋',
                '青椒土豆丝',
                '凉拌黄瓜',
                '银耳莲子汤',
                '绿豆汤',
                '红豆沙',
                '双皮奶',
                '杨枝甘露'
            ]),
            'subtitle' => fake()->optional(0.7)->randomElement([
                '经典家常菜',
                '川菜代表',
                '粤菜精品',
                '湘菜特色',
                '鲁菜传统',
                '营养健康',
                '清淡爽口',
                '香辣开胃',
                '甜而不腻',
                '老少皆宜'
            ]),
            'content' => fake()->paragraphs(3, true),
            'order_count' => fake()->numberBetween(0, 100),
            'view_count' => fake()->numberBetween(0, 500),
            'menu_level_id' => MenuLevel::inRandomOrder()->first()?->id,
            'is_visible' => fake()->boolean(85), // 85%概率为true
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * 配置模型创建后的回调
     */
    public function configure()
    {
        return $this->afterCreating(function (Menu $menu) {
            // 如果menu_level_id为空，随机分配一个分类
            if (!$menu->menu_level_id) {
                $menuLevel = MenuLevel::inRandomOrder()->first();
                if ($menuLevel) {
                    $menu->update(['menu_level_id' => $menuLevel->id]);
                }
            }
        });
    }

    /**
     * 创建热门菜谱（高点击量）
     */
    public function popular(): static
    {
        return $this->state(fn(array $attributes) => [
            'order_count' => fake()->numberBetween(50, 200),
            'view_count' => fake()->numberBetween(200, 1000),
            'sort_order' => fake()->numberBetween(80, 100),
        ]);
    }

    /**
     * 创建隐藏菜谱
     */
    public function hidden(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_visible' => false,
        ]);
    }

    /**
     * 创建特定分类的菜谱
     */
    public function forLevel(MenuLevel $menuLevel): static
    {
        return $this->state(fn(array $attributes) => [
            'menu_level_id' => $menuLevel->id,
        ]);
    }
}

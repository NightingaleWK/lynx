<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['卷心菜', '辣椒', '西红柿', '土豆', '胡萝卜', '洋葱', '大蒜', '生姜', '白菜', '菠菜']),
            'en_name' => fake()->randomElement(['cabbage', 'pepper', 'tomato', 'potato', 'carrot', 'onion', 'garlic', 'ginger', 'lettuce', 'spinach']),
            'alias' => fake()->optional()->sentence(2),
            'description' => fake()->paragraph(2),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaterialLevel>
 */
class MaterialLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['蔬菜类', '水果类', '肉类', '海鲜类', '调味料', '主食类', '饮品类', '零食类']),
            'sort_order' => fake()->numberBetween(1, 100),
            'is_visible' => true,
        ];
    }
}

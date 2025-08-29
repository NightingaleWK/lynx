<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['个', '箱', '件', '套', '条', '包', '卷', '盘', '米', '千克', '升']),
            'en_name' => fake()->randomElement(['pcs', 'box', 'set', 'piece', 'roll', 'kg', 'm', 'l']),
            'remark' => fake()->sentence(3),
        ];
    }
}

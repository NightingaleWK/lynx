<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\MaterialLevel;
use App\Models\Unit;
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
            'name' => fake()->word(),
            'en_name' => fake()->optional()->word(),
            'alias' => fake()->optional()->word(),
            'description' => fake()->optional()->paragraph(2),
            'material_level_id' => MaterialLevel::factory(),
        ];
    }

    /**
     * 配置模型创建后的回调
     */
    public function configure()
    {
        return $this->afterCreating(function (Material $material) {
            // 随机关联 2-4 个单位
            $units = Unit::inRandomOrder()->take(fake()->numberBetween(1, 2))->get();
            $material->units()->attach($units);
        });
    }
}

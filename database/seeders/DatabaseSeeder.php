<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class, // 必须先运行，创建角色
            UserSeeder::class, // 必须第二，创建用户并分配角色

            IngredientAisleSeeder::class, // 基础数据
            CategorySeeder::class,        // 基础数据
            IngredientSeeder::class,      // 依赖 IngredientAisle
            DishSeeder::class,            // 依赖 Category & Ingredient
        ]);
    }
}

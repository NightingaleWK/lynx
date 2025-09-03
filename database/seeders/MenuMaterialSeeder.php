<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Material;
use App\Models\Unit;
use App\Models\MenuMaterial;
use Illuminate\Database\Seeder;

class MenuMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 确保有数据
        if (Menu::count() === 0 || Material::count() === 0 || Unit::count() === 0) {
            $this->command->info('请先运行 MenuSeeder, MaterialSeeder, UnitSeeder');
            return;
        }

        // 获取一些示例数据
        $menus = Menu::take(5)->get();
        $materials = Material::take(10)->get();
        $units = Unit::take(5)->get();

        if ($menus->isEmpty() || $materials->isEmpty() || $units->isEmpty()) {
            $this->command->info('数据不足，无法创建关联');
            return;
        }

        foreach ($menus as $menu) {
            // 为每个菜谱随机添加2-4个物料
            $randomMaterials = $materials->random(rand(2, 4));

            foreach ($randomMaterials as $material) {
                // 获取该物料可用的单位
                $availableUnits = $material->units;

                if ($availableUnits->isNotEmpty()) {
                    $unit = $availableUnits->random();
                    $quantity = rand(1, 5) + (rand(0, 100) / 100); // 1.00 到 5.99 之间的随机数

                    MenuMaterial::create([
                        'menu_id' => $menu->id,
                        'material_id' => $material->id,
                        'unit_id' => $unit->id,
                        'quantity' => $quantity,
                    ]);
                }
            }
        }

        $this->command->info('已为 ' . $menus->count() . ' 个菜谱添加了物料关联');
    }
}

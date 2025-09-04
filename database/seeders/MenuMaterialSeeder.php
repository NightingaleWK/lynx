<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Material;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 获取菜谱
        $potatoSoupMenu = Menu::where('title', '土豆汤')->first();
        $cucumberEggMenu = Menu::where('title', '黄瓜炒鸡蛋')->first();
        $tofuSkinPepperMenu = Menu::where('title', '青椒炒豆腐皮')->first();

        if (!$potatoSoupMenu || !$cucumberEggMenu || !$tofuSkinPepperMenu) {
            $this->command->error('未找到菜谱，请先运行 MenuSeeder');
            return;
        }

        // 菜谱物料清单
        $potatoSoupMaterials = [
            ['name' => '土豆', 'unit' => '个', 'quantity' => 1],
            ['name' => '大蒜', 'unit' => '瓣', 'quantity' => 3],
            ['name' => '盐', 'unit' => '茶勺', 'quantity' => 1],
            ['name' => '白胡椒粉', 'unit' => '茶勺', 'quantity' => 1],
            ['name' => '味精', 'unit' => '茶勺', 'quantity' => 1],
            ['name' => '醋', 'unit' => '勺', 'quantity' => 1],
            ['name' => '耗油', 'unit' => '勺', 'quantity' => 1],
            ['name' => '葱花', 'unit' => '勺', 'quantity' => 1],
        ];

        $cucumberEggMaterials = [
            ['name' => '黄瓜', 'unit' => '根', 'quantity' => 3],
            ['name' => '鸡蛋', 'unit' => '个', 'quantity' => 4],
            ['name' => '大蒜', 'unit' => '瓣', 'quantity' => 3],
            ['name' => '盐', 'unit' => '茶勺', 'quantity' => 1],
            ['name' => '味精', 'unit' => '茶勺', 'quantity' => 1],
        ];

        $tofuSkinPepperMaterials = [
            ['name' => '豆腐皮', 'unit' => '张', 'quantity' => 2],
            ['name' => '菜椒', 'unit' => '个', 'quantity' => 2],
            ['name' => '青椒', 'unit' => '个', 'quantity' => 1],
            ['name' => '小苏打', 'unit' => '茶勺', 'quantity' => 1],
            ['name' => '小炒调味剂', 'unit' => '勺', 'quantity' => 2],
            ['name' => '大蒜', 'unit' => '瓣', 'quantity' => 3],
        ];

        // 创建土豆汤物料关联
        foreach ($potatoSoupMaterials as $materialData) {
            $material = Material::where('name', $materialData['name'])->first();
            $unit = Unit::where('name', $materialData['unit'])->first();

            if ($material && $unit) {
                DB::table('menu_materials')->insert([
                    'menu_id' => $potatoSoupMenu->id,
                    'material_id' => $material->id,
                    'unit_id' => $unit->id,
                    'quantity' => $materialData['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->command->warn("未找到物料: {$materialData['name']} 或单位: {$materialData['unit']}");
            }
        }

        // 创建黄瓜炒鸡蛋物料关联
        foreach ($cucumberEggMaterials as $materialData) {
            $material = Material::where('name', $materialData['name'])->first();
            $unit = Unit::where('name', $materialData['unit'])->first();

            if ($material && $unit) {
                DB::table('menu_materials')->insert([
                    'menu_id' => $cucumberEggMenu->id,
                    'material_id' => $material->id,
                    'unit_id' => $unit->id,
                    'quantity' => $materialData['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->command->warn("未找到物料: {$materialData['name']} 或单位: {$materialData['unit']}");
            }
        }

        // 创建青椒炒豆腐皮物料关联
        foreach ($tofuSkinPepperMaterials as $materialData) {
            $material = Material::where('name', $materialData['name'])->first();
            $unit = Unit::where('name', $materialData['unit'])->first();

            if ($material && $unit) {
                DB::table('menu_materials')->insert([
                    'menu_id' => $tofuSkinPepperMenu->id,
                    'material_id' => $material->id,
                    'unit_id' => $unit->id,
                    'quantity' => $materialData['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->command->warn("未找到物料: {$materialData['name']} 或单位: {$materialData['unit']}");
            }
        }

        $this->command->info('已创建 ' . DB::table('menu_materials')->count() . ' 个菜谱物料关联');
    }
}

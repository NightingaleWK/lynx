<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialLevel;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 获取分类ID
        $vegetableLevel = MaterialLevel::where('name', '蔬菜')->first();
        $seasoningLevel = MaterialLevel::where('name', '调料')->first();
        $eggLevel = MaterialLevel::where('name', '蛋类')->first();
        $tofuLevel = MaterialLevel::where('name', '豆制品')->first();

        // 根据菜谱需要的物料创建数据
        $materials = [
            [
                'name' => '土豆',
                'en_name' => 'potato',
                'alias' => '马铃薯',
                'description' => '一人食选小土豆，二人食选大土豆或两个小土豆',
                'material_level_id' => $vegetableLevel->id,
            ],
            [
                'name' => '大蒜',
                'en_name' => 'garlic',
                'alias' => '蒜头',
                'description' => '拍散剁碎成沫',
                'material_level_id' => $vegetableLevel->id,
            ],
            [
                'name' => '葱花',
                'en_name' => 'chopped green onion',
                'alias' => '葱末',
                'description' => '切碎的青葱',
                'material_level_id' => $vegetableLevel->id,
            ],
            [
                'name' => '黄瓜',
                'en_name' => 'cucumber',
                'alias' => '青瓜',
                'description' => '清爽脆嫩的蔬菜',
                'material_level_id' => $vegetableLevel->id,
            ],
            [
                'name' => '鸡蛋',
                'en_name' => 'egg',
                'alias' => '鸡子',
                'description' => '新鲜鸡蛋',
                'material_level_id' => $eggLevel->id,
            ],
            [
                'name' => '豆腐皮',
                'en_name' => 'tofu skin',
                'alias' => '千张',
                'description' => '又称千张',
                'material_level_id' => $tofuLevel->id,
            ],
            [
                'name' => '菜椒',
                'en_name' => 'bell pepper',
                'alias' => '甜椒',
                'description' => '无辣味的甜椒',
                'material_level_id' => $vegetableLevel->id,
            ],
            [
                'name' => '青椒',
                'en_name' => 'green pepper',
                'alias' => '青辣椒',
                'description' => '有辣味的青椒',
                'material_level_id' => $vegetableLevel->id,
            ],
            [
                'name' => '小苏打',
                'en_name' => 'baking soda',
                'alias' => '食碱',
                'description' => '又称食碱，用于改善豆腐皮口感',
                'material_level_id' => $seasoningLevel->id,
            ],
            [
                'name' => '小炒调味剂',
                'en_name' => 'stir-fry seasoning',
                'alias' => '仲景家家常小炒调味剂',
                'description' => '仲景家家常小炒调味剂，超市有卖，快速调味无需自配',
                'material_level_id' => $seasoningLevel->id,
            ],
            [
                'name' => '盐',
                'en_name' => 'salt',
                'alias' => '食盐',
                'description' => '调味用盐',
                'material_level_id' => $seasoningLevel->id,
            ],
            [
                'name' => '白胡椒粉',
                'en_name' => 'white pepper powder',
                'alias' => '白胡椒',
                'description' => '白色胡椒粉',
                'material_level_id' => $seasoningLevel->id,
            ],
            [
                'name' => '味精',
                'en_name' => 'MSG',
                'alias' => '谷氨酸钠',
                'description' => '增鲜调味料',
                'material_level_id' => $seasoningLevel->id,
            ],
            [
                'name' => '醋',
                'en_name' => 'vinegar',
                'alias' => '食醋',
                'description' => '酸性调味料',
                'material_level_id' => $seasoningLevel->id,
            ],
            [
                'name' => '耗油',
                'en_name' => 'oyster sauce',
                'alias' => '蚝油',
                'description' => '蚝油调味料',
                'material_level_id' => $seasoningLevel->id,
            ],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }

        // 建立物料与单位的关联关系
        $this->createMaterialUnitRelations();

        $this->command->info('已创建 ' . Material::count() . ' 个物料');
    }

    /**
     * 创建物料与单位的关联关系
     */
    private function createMaterialUnitRelations(): void
    {
        $materials = Material::all();
        $units = Unit::all();

        $relations = [
            '土豆' => ['个'],
            '大蒜' => ['瓣'],
            '葱花' => ['勺'],
            '黄瓜' => ['根'],
            '鸡蛋' => ['个'],
            '豆腐皮' => ['张'],
            '菜椒' => ['个'],
            '青椒' => ['个'],
            '小苏打' => ['茶勺'],
            '小炒调味剂' => ['勺'],
            '盐' => ['茶勺'],
            '白胡椒粉' => ['茶勺'],
            '味精' => ['茶勺'],
            '醋' => ['勺'],
            '耗油' => ['勺'],
        ];

        foreach ($relations as $materialName => $unitNames) {
            $material = $materials->where('name', $materialName)->first();
            if ($material) {
                foreach ($unitNames as $unitName) {
                    $unit = $units->where('name', $unitName)->first();
                    if ($unit) {
                        $material->units()->attach($unit->id);
                    }
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 确保有菜谱分类数据
        if (MenuLevel::count() === 0) {
            $this->command->info('没有找到菜谱分类数据，请先运行 MenuLevelSeeder');
            return;
        }

        // 创建一些具体的示例菜谱
        $this->createSampleMenus();

        // 使用 factory 生成10个随机菜谱
        Menu::factory(10)->create();

        $this->command->info('已创建 ' . Menu::count() . ' 个菜谱');
    }

    /**
     * 创建示例菜谱
     */
    private function createSampleMenus(): void
    {
        $defaultLevel = MenuLevel::where('name', '默认')->first();
        $stirFryLevel = MenuLevel::where('name', '炒菜')->first();
        $soupLevel = MenuLevel::where('name', '煲汤')->first();
        $dessertLevel = MenuLevel::where('name', '甜品')->first();

        // 示例菜谱1：红烧肉
        Menu::factory()->create([
            'title'          => '红烧肉',
            'subtitle'       => '经典家常菜',
            'content'        => '红烧肉是一道经典的中式家常菜，选用五花肉为主料，配以生抽、老抽、冰糖等调料，经过炒糖色、炖煮等工序制作而成。成品色泽红亮，肥而不腻，瘦而不柴，口感软糯，味道鲜美。',
            'order_count'    => 156,
            'view_count'     => 892,
            'menu_level_id'  => $stirFryLevel?->id ?? $defaultLevel?->id,
            'is_visible'     => true,
            'sort_order'     => 95,
        ]);

        // 示例菜谱2：银耳莲子汤
        Menu::factory()->create([
            'title'          => '银耳莲子汤',
            'subtitle'       => '营养滋补',
            'content'        => '银耳莲子汤是一道传统的滋补甜品，具有润肺养颜、滋阴润燥的功效。选用优质银耳、莲子、红枣等食材，经过长时间炖煮，汤汁清甜，银耳软糯，莲子香甜，是秋冬季节的养生佳品。',
            'order_count'    => 89,
            'view_count'     => 456,
            'menu_level_id'  => $soupLevel?->id ?? $defaultLevel?->id,
            'is_visible'     => true,
            'sort_order'     => 88,
        ]);

        // 示例菜谱3：双皮奶
        Menu::factory()->create([
            'title'          => '双皮奶',
            'subtitle'       => '广式甜品',
            'content'        => '双皮奶是广东传统甜品，以牛奶、鸡蛋、白糖为主要原料制作而成。制作工艺独特，需要两次形成奶皮，因此得名"双皮奶"。成品口感嫩滑，奶香浓郁，甜而不腻，是夏日消暑的佳品。',
            'order_count'    => 234,
            'view_count'     => 1205,
            'menu_level_id'  => $dessertLevel?->id ?? $defaultLevel?->id,
            'is_visible'     => true,
            'sort_order'     => 92,
        ]);

        // 示例菜谱4：宫保鸡丁
        Menu::factory()->create([
            'title'          => '宫保鸡丁',
            'subtitle'       => '川菜代表',
            'content'        => '宫保鸡丁是四川传统名菜，以鸡胸肉、花生米、干辣椒为主要食材，配以葱、姜、蒜等调料炒制而成。成品色泽红亮，鸡肉嫩滑，花生香脆，味道麻辣鲜香，是川菜中的经典代表。',
            'order_count'    => 198,
            'view_count'     => 967,
            'menu_level_id'  => $stirFryLevel?->id ?? $defaultLevel?->id,
            'is_visible'     => true,
            'sort_order'     => 90,
        ]);

        // 示例菜谱5：隐藏菜谱示例
        Menu::factory()->create([
            'title'          => '秘制酱料',
            'subtitle'       => '内部配方',
            'content'        => '这是内部使用的秘制酱料配方，暂时不对外公开。',
            'order_count'    => 12,
            'view_count'     => 45,
            'menu_level_id'  => $defaultLevel?->id,
            'is_visible'     => false,
            'sort_order'     => 10,
        ]);
    }
}

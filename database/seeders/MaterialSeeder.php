<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Material::factory()->create([
            'name' => '卷心菜',
            'en_name' => 'cabbage',
            'alias' => '圆白菜、高丽菜',
            'description' => '卷心菜是一种常见蔬菜，又称圆白菜、高丽菜等，植物学上称为结球甘蓝。它富含维生素（如C、K、叶酸），具有抗氧化、抗癌、促进胃肠蠕动、缓解咽喉及胃痛等多种保健功效。',
        ]);

        Material::factory()->create([
            'name' => '辣椒',
            'en_name' => 'pepper',
            'alias' => '番椒',
            'description' => '辣椒，又名番椒，是一种茄科辣椒属的植物，原产于中南美洲，现已遍布全球栽培。其果实富含维生素C，含有辣椒素而味辣，是重要的调味品和蔬菜，也可加工成多种食品',
        ]);

        Material::factory()->create([
            'name' => '西红柿',
            'en_name' => 'tomato',
            'alias' => '番茄',
            'description' => '西红柿无疑是人人喜爱的蔬菜。它易于栽种，抗病虫害能力强，品种多且产量大。',
        ]);
    }
}

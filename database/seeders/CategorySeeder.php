<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            '下饭热菜',
            '开胃凉菜',
            '滋补汤羹',
            '主食点心',
            '西式简餐',
            '减脂轻食',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}

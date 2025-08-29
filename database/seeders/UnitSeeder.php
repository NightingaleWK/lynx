<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::factory()->create([
            'name' => '个',
            'en_name' => 'pcs',
            'remark' => '',
        ]);

        Unit::factory()->create([
            'name' => '箱',
            'en_name' => 'box',
            'remark' => '',
        ]);

        Unit::factory()->create([
            'name' => '件',
            'en_name' => 'pcs',
            'remark' => '',
        ]);

        Unit::factory()->create([
            'name' => '套',
            'en_name' => 'pcs',
            'remark' => '',
        ]);

        Unit::factory()->create([
            'name' => '条',
            'en_name' => 'pcs',
            'remark' => '',
        ]);

        Unit::factory()->create([
            'name' => '包',
            'en_name' => 'pcs',
            'remark' => '',
        ]);

        Unit::factory()->create([
            'name' => '卷',
            'en_name' => 'pcs',
            'remark' => '',
        ]);

        Unit::factory()->create([
            'name' => '盘',
            'en_name' => 'pcs',
            'remark' => '',
        ]);
    }
}

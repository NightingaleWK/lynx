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
            'remark' => '个',
        ]);

        Unit::factory()->create([
            'name' => '箱',
            'en_name' => 'box',
            'remark' => '箱',
        ]);

        Unit::factory()->create([
            'name' => '件',
            'en_name' => 'pcs',
            'remark' => '件',
        ]);

        Unit::factory()->create([
            'name' => '套',
            'en_name' => 'pcs',
            'remark' => '套',
        ]);

        Unit::factory()->create([
            'name' => '条',
            'en_name' => 'pcs',
            'remark' => '条',
        ]);

        Unit::factory()->create([
            'name' => '包',
            'en_name' => 'pcs',
            'remark' => '包',
        ]);

        Unit::factory()->create([
            'name' => '卷',
            'en_name' => 'pcs',
            'remark' => '卷',
        ]);

        Unit::factory()->create([
            'name' => '盘',
            'en_name' => 'pcs',
            'remark' => '盘',
        ]);
    }
}

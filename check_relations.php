<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// 检查物料和单位关联
$material = App\Models\Material::with('units')->first();

echo "Material: " . $material->name . "\n";
echo "Units count: " . $material->units->count() . "\n";

if ($material->units->count() > 0) {
    echo "Associated units:\n";
    foreach ($material->units as $unit) {
        echo "- " . $unit->name . " (ID: " . $unit->id . ")\n";
    }
} else {
    echo "No units associated with this material.\n";
}

// 检查数据库中间表
$pivotCount = DB::table('materials_units')->count();
echo "\nTotal pivot records: " . $pivotCount . "\n";

if ($pivotCount > 0) {
    $pivots = DB::table('materials_units')->limit(3)->get();
    echo "Sample pivot records:\n";
    foreach ($pivots as $pivot) {
        echo "- Material ID: {$pivot->material_id}, Unit ID: {$pivot->unit_id}\n";
    }
}

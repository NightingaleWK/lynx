<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materials_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->onDelete('restrict')->comment('物料ID');
            $table->foreignId('unit_id')->constrained()->onDelete('restrict')->comment('单位ID');
            $table->timestamps();

            // 添加唯一约束，防止重复关联
            $table->unique(['material_id', 'unit_id'], 'materials_units_unique');

            $table->comment('物料-单位关系表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials_units');
    }
};

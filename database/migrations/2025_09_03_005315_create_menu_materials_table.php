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
        Schema::create('menu_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade')->comment('菜谱ID');
            $table->foreignId('material_id')->constrained()->onDelete('cascade')->comment('物料ID');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade')->comment('单位ID');
            $table->decimal('quantity', 8, 2)->default(1)->comment('数量');
            $table->timestamps();

            // 添加唯一约束，防止同一菜谱中重复添加同一物料
            $table->unique(['menu_id', 'material_id'], 'menu_materials_unique');

            // 添加索引
            $table->index('menu_id');
            $table->index('material_id');
            $table->index('unit_id');

            $table->comment('菜谱-物料关系表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_materials');
    }
};

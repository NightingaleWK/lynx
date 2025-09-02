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
        Schema::create('menu_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('分类名称');
            $table->unsignedInteger('sort_order')->default(0)->comment('排序权重。数值越大越靠前');
            $table->boolean('is_visible')->default(true)->comment('是否显示');
            $table->timestamps();

            // 添加索引
            $table->index('sort_order');
            $table->index('is_visible');

            $table->comment('菜谱分类表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_levels');
    }
};

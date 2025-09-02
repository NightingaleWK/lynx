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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('标题');
            $table->string('subtitle')->nullable()->comment('副标题');
            $table->foreignId('menu_level_id')->nullable()->constrained('menu_levels')->onDelete('set null')->comment('菜谱分类ID');
            $table->longText('content')->nullable()->comment('正文');
            $table->unsignedInteger('order_count')->default(0)->comment('点菜次数');
            $table->unsignedInteger('view_count')->default(0)->comment('浏览次数');
            $table->boolean('is_visible')->default(true)->comment('是否显示');
            $table->unsignedInteger('sort_order')->default(0)->comment('排序权重。数值越大越靠前');
            $table->timestamps();

            // 添加索引
            $table->index('menu_level_id');
            $table->index('is_visible');
            $table->index('sort_order');
            $table->index('order_count');
            $table->index('view_count');

            $table->comment('菜谱表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

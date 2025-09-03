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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade')->comment('订单ID');
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade')->comment('菜品ID');
            $table->unsignedInteger('quantity')->default(1)->comment('数量');
            $table->text('item_remarks')->nullable()->comment('单品备注');
            $table->timestamps();

            // 添加索引
            $table->index('order_id');
            $table->index('menu_id');

            $table->comment('订单项表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

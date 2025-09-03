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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->comment('订单号');
            $table->datetime('dining_time')->comment('用餐时间');
            $table->text('remarks')->nullable()->comment('备注信息');
            $table->enum('status', ['pending', 'confirmed', 'cooking', 'completed', 'cancelled'])
                ->default('pending')
                ->comment('订单状态：待确认、已确认、制作中、已完成、已取消');
            $table->timestamps();

            // 添加索引
            $table->index('order_number');
            $table->index('status');
            $table->index('dining_time');
            $table->index('created_at');

            $table->comment('订单表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

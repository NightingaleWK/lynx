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
            $table->id()->comment('ID');
            $table->foreignId('user_id')->constrained()->comment('用户ID');
            $table->string('status')->default('pending')->comment('订单状态');
            $table->date('meal_date')->comment('用餐日期');
            $table->string('meal_period')->comment('用餐时段'); // lunch, dinner, snack
            $table->text('customer_note')->nullable()->comment('整单备注'); // Partner note
            $table->text('chef_note')->nullable()->comment('大厨备注'); // Chef note
            $table->timestamps();

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

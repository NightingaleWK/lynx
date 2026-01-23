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
            $table->id()->comment('ID');
            $table->foreignId('order_id')->constrained()->cascadeOnDelete()->comment('订单ID');
            $table->foreignId('dish_id')->constrained()->comment('菜品ID');
            $table->integer('quantity')->comment('份数');
            $table->string('note')->nullable()->comment('单品备注');
            $table->timestamps();

            $table->comment('订单明细表');
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

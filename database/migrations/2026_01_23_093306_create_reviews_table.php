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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('order_id')->constrained()->cascadeOnDelete()->comment('订单ID');
            $table->integer('rating')->comment('评分');
            $table->text('comment')->nullable()->comment('评价内容');
            $table->timestamps();

            $table->comment('评价表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

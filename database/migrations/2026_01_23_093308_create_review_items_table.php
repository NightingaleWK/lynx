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
        Schema::create('review_items', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('review_id')->constrained()->cascadeOnDelete()->comment('评价ID');
            $table->foreignId('dish_id')->constrained()->comment('菜品ID');
            $table->integer('rating')->comment('单品评分');
            $table->timestamps();

            $table->comment('评价明细表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_items');
    }
};

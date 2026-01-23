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
        Schema::create('dish_ingredients', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('dish_id')->constrained()->cascadeOnDelete()->comment('菜品ID');
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete()->comment('食材ID');
            $table->decimal('quantity', 10, 2)->default(0)->comment('数量 (计算用)');
            $table->string('unit')->comment('单位 (快照)');
            $table->string('remark')->nullable()->comment('备注 (展示用)');
            $table->timestamps();

            $table->comment('菜品食材关联表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_ingredients');
    }
};

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
        Schema::create('dishes', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('category_id')->constrained()->comment('分类ID');
            $table->string('name')->comment('菜品名称');
            $table->text('description')->nullable()->comment('菜品描述');
            $table->timestamp('last_eaten_at')->nullable()->comment('上次食用时间');
            $table->integer('frequency')->default(0)->comment('食用频次');
            $table->timestamps();

            $table->comment('菜品表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};

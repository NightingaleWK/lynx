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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('名称');
            $table->string('en_name')->nullable()->comment('英文缩写');
            $table->string('alias')->nullable()->comment('别名');
            $table->text('description')->nullable()->comment('描述');
            $table->timestamps();

            $table->comment('物料表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};

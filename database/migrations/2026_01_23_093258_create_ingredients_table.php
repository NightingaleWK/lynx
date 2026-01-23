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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->foreignId('aisle_id')->constrained('ingredient_aisles')->comment('购买分区ID');
            $table->string('name')->comment('食材名称');
            $table->string('base_unit')->comment('基础单位'); // g, ml, pc
            $table->timestamps();

            $table->comment('食材表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};

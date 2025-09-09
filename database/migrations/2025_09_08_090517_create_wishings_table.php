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
        Schema::create('wishings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('许愿人用户ID');
            $table->text('content')->comment('许愿内容');
            $table->enum('status', ['pending', 'accepted', 'fulfilled', 'rejected'])
                ->default('pending')
                ->comment('许愿状态：待回应、已受理、已实现、已抛弃');
            $table->text('response')->nullable()->comment('主理人回应内容');
            $table->timestamp('accepted_at')->nullable()->comment('受理时间');
            $table->timestamp('fulfilled_at')->nullable()->comment('实现时间');
            $table->timestamp('rejected_at')->nullable()->comment('抛弃时间');
            $table->timestamps();

            // 添加索引
            $table->index('status');
            $table->index('user_id');
            $table->index('created_at');
            $table->index('accepted_at');

            $table->comment('许愿池表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishings');
    }
};

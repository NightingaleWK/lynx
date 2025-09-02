<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => '楷楷',
            'email' => 'admin@admin.com',
            'is_active' => true,
            'password' => bcrypt('Admin@123'),
        ]);

        User::factory()->create([
            'name' => '敏敏',
            'email' => 'msm@admin.com',
            'is_active' => true,
            'password' => bcrypt('Admin@123'),
        ]);

        $this->command->info('已创建 ' . User::count() . ' 个用户');
    }
}

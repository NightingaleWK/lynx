<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

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
    }
}

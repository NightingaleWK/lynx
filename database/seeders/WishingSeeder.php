<?php

namespace Database\Seeders;

use App\Models\Wishing;
use Illuminate\Database\Seeder;

class WishingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 创建各种状态的许愿数据
        $this->createWishingsByStatus();

        $this->command->info('许愿池测试数据创建完成！');
    }

    /**
     * 按状态创建许愿数据
     */
    private function createWishingsByStatus(): void
    {
        // 创建待回应许愿 (5个)
        Wishing::factory()
            ->count(5)
            ->pending()
            ->create();

        $this->command->info('创建了 5 个待回应许愿');

        // 创建已受理许愿 (3个)
        Wishing::factory()
            ->count(3)
            ->accepted()
            ->create();

        $this->command->info('创建了 3 个已受理许愿');

        // 创建已实现许愿 (4个)
        Wishing::factory()
            ->count(4)
            ->fulfilled()
            ->create();

        $this->command->info('创建了 4 个已实现许愿');

        // 创建已抛弃许愿 (2个)
        Wishing::factory()
            ->count(2)
            ->rejected()
            ->create();

        $this->command->info('创建了 2 个已抛弃许愿');

        // 创建一些随机状态的许愿 (6个)
        Wishing::factory()
            ->count(6)
            ->create();

        $this->command->info('创建了 6 个随机状态许愿');

        // 显示统计信息
        $this->displayStatistics();
    }

    /**
     * 显示许愿统计信息
     */
    private function displayStatistics(): void
    {
        $total = Wishing::count();
        $pending = Wishing::pending()->count();
        $accepted = Wishing::accepted()->count();
        $fulfilled = Wishing::fulfilled()->count();
        $rejected = Wishing::rejected()->count();

        $this->command->info("\n=== 许愿池统计 ===");
        $this->command->info("总许愿数: {$total}");
        $this->command->info("待回应: {$pending}");
        $this->command->info("已受理: {$accepted}");
        $this->command->info("已实现: {$fulfilled}");
        $this->command->info("已抛弃: {$rejected}");
        $this->command->info("================\n");
    }
}

<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 获取一些菜品
        $menus = Menu::visible()->take(5)->get();

        if ($menus->isEmpty()) {
            $this->command->warn('没有可用的菜品，请先运行菜品相关的 seeder');
            return;
        }

        // 创建几个测试订单
        $orders = [
            [
                'order_number' => Order::generateOrderNumber(),
                'dining_time' => now()->addHours(2),
                'remarks' => '不要辣，多放蒜',
                'status' => Order::STATUS_PENDING,
            ],
            [
                'order_number' => Order::generateOrderNumber(),
                'dining_time' => now()->addHours(1),
                'remarks' => '少油少盐',
                'status' => Order::STATUS_CONFIRMED,
            ],
            [
                'order_number' => Order::generateOrderNumber(),
                'dining_time' => now()->addMinutes(30),
                'remarks' => null,
                'status' => Order::STATUS_COOKING,
            ],
            [
                'order_number' => Order::generateOrderNumber(),
                'dining_time' => now()->subHours(1),
                'remarks' => '打包带走',
                'status' => Order::STATUS_COMPLETED,
            ],
        ];

        foreach ($orders as $orderData) {
            $order = Order::create($orderData);

            // 为每个订单随机添加1-3个菜品
            $selectedMenus = $menus->random(rand(1, 3));

            foreach ($selectedMenus as $menu) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => rand(1, 3),
                    'item_remarks' => rand(0, 1) ? ['不要香菜', '多加辣椒', '少放盐', '不要葱'][array_rand(['不要香菜', '多加辣椒', '少放盐', '不要葱'])] : null,
                ]);
            }

            $this->command->info("创建订单: {$order->order_number} (状态: {$order->status_label})");
        }

        $this->command->info('订单测试数据创建完成！');
    }
}

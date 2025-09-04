<?php

namespace App\Observers;

use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * 当OrderItem创建后，更新关联Menu的点单次数
     */
    public function created(OrderItem $orderItem): void
    {
        try {
            if ($orderItem->menu) {
                // 根据quantity增加点单次数
                $orderItem->menu->increment('order_count', $orderItem->quantity);

                Log::info("更新Menu点单次数", [
                    'menu_id' => $orderItem->menu->id,
                    'menu_title' => $orderItem->menu->title,
                    'quantity' => $orderItem->quantity,
                    'order_id' => $orderItem->order_id,
                    'order_number' => $orderItem->order->order_number ?? 'N/A'
                ]);
            }
        } catch (\Exception $e) {
            // 记录日志但不中断流程
            Log::error('更新Menu点单次数失败', [
                'order_item_id' => $orderItem->id,
                'order_id' => $orderItem->order_id,
                'menu_id' => $orderItem->menu_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}

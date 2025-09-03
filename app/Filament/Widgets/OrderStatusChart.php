<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChart extends ChartWidget
{
    protected static ?int $sort = 6;

    protected ?string $maxHeight = '275px';

    protected function getData(): array
    {
        $statuses = Order::getStatusOptionsText();

        $data = [];

        foreach ($statuses as $status => $label) {
            $count = Order::byStatus($status)->count();
            $data[] = [
                'status' => $label,
                'count' => $count,
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => __('widgets.order_quantity'),
                    'data' => array_column($data, 'count'),
                    'backgroundColor' => [
                        '#f59e0b', // warning - 待确认
                        '#3b82f6', // info - 已确认
                        '#8b5cf6', // primary - 制作中
                        '#10b981', // success - 已完成
                        '#ef4444', // danger - 已取消
                    ],
                ],
            ],
            'labels' => array_column($data, 'status'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

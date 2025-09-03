<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TodayOrdersChart extends ChartWidget
{
    protected static ?int $sort = 7;

    protected function getData(): array
    {
        $hours = [];
        $counts = [];

        // 获取今日24小时的订单数量
        for ($i = 0; $i < 24; $i++) {
            $startTime = Carbon::today()->addHours($i);
            $endTime = $startTime->copy()->addHour();

            $count = Order::whereBetween('created_at', [$startTime, $endTime])->count();

            $hours[] = $startTime->format('H:00');
            $counts[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => __('widgets.order_quantity'),
                    'data' => $counts,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $hours,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

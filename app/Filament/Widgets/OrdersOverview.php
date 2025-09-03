<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersOverview extends BaseWidget
{
    protected static ?int $sort = 2;
    protected function getStats(): array
    {
        return [
            Stat::make(__('widgets.today_orders'), Order::today()->count())
                ->description(__('widgets.today_orders_desc'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),

            Stat::make(__('widgets.pending_orders'), Order::pending()->count())
                ->description(__('widgets.pending_orders_desc'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make(__('widgets.in_progress_orders'), Order::inProgress()->count())
                ->description(__('widgets.in_progress_orders_desc'))
                ->descriptionIcon('heroicon-m-play-circle')
                ->color('info'),

            Stat::make(__('widgets.completed_orders'), Order::byStatus(Order::STATUS_COMPLETED)->count())
                ->description(__('widgets.completed_orders_desc'))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}

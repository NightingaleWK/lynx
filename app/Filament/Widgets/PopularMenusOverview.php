<?php

namespace App\Filament\Widgets;

use App\Models\Menu;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PopularMenusOverview extends BaseWidget
{
    protected static ?int $sort = 3;
    protected function getStats(): array
    {
        $popularMenus = Menu::visible()->popular()->limit(3)->get();

        $stats = [];

        foreach ($popularMenus as $index => $menu) {
            $rank = $index + 1;
            $stats[] = Stat::make(
                __('widgets.popular_rank', ['rank' => $rank, 'title' => $menu->title]),
                $menu->order_count . ' 次'
            )
                ->description(__('widgets.order_count'))
                ->descriptionIcon('heroicon-m-fire')
                ->color($index === 0 ? 'danger' : ($index === 1 ? 'warning' : 'success'));
        }

        // 如果没有数据，显示默认统计
        if (empty($stats)) {
            $stats[] = Stat::make(__('widgets.no_data'), '0')
                ->description(__('widgets.no_orders_yet'))
                ->descriptionIcon('heroicon-m-information-circle')
                ->color('gray');
        }

        return $stats;
    }
}

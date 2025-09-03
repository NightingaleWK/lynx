<?php

namespace App\Filament\Widgets;

use App\Models\Menu;
use App\Models\Material;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemOverview extends BaseWidget
{
    protected static ?int $sort = 4;
    protected function getStats(): array
    {
        return [
            Stat::make(__('widgets.total_menus'), Menu::count())
                ->description(__('widgets.total_menus_desc'))
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make(__('widgets.visible_menus'), Menu::visible()->count())
                ->description(__('widgets.visible_menus_desc'))
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),

            Stat::make(__('widgets.material_types'), Material::count())
                ->description(__('widgets.material_types_desc'))
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),

            Stat::make(__('widgets.total_orders'), Order::count())
                ->description(__('widgets.total_orders_desc'))
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
        ];
    }
}

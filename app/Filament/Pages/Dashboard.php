<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\WelcomeMessage;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    // public function getTitle(): string
    // {
    //     return '点餐系统控制台';
    // }

    // public function getHeading(): string
    // {
    //     return '欢迎使用点餐系统';
    // }

    // public function getSubheading(): string
    // {
    //     return '实时监控订单状态和系统数据';
    // }
}

<?php

namespace App\Filament\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup implements HasLabel
{
    case Dish;
    case Ingredient;
    case System;

    public function getLabel(): string
    {
        return match ($this) {
            self::Dish => '菜品管理',
            self::Ingredient => '食材管理',
            self::System => '系统管理',
        };
    }
}

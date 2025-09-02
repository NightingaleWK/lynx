<?php

namespace App\Filament\Resources\MenuLevels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class MenuLevelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('menu-level.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('menu-level.name'))
                    ->columnSpan(1),

                TextInput::make('sort_order')
                    ->label(__('menu-level.sort_order'))
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(999)
                    ->placeholder('0')
                    ->columnSpan(1),

                ToggleButtons::make('is_visible')
                    ->label(__('menu-level.is_visible'))
                    ->options([
                        true => '是',
                        false => '否'
                    ])
                    ->colors([
                        true => 'success',
                        false => 'danger',
                    ])
                    ->icons([
                        true => Heroicon::Check,
                        false => Heroicon::XMark,
                    ])
                    ->inline()
                    ->default(true)
                    ->columnSpan(1),
            ])
            ->columns([
                'sm' => 1,
                'md' => 3,
                'lg' => 3,
                'xl' => 3,
                '2xl' => 3,
            ]);
    }
}

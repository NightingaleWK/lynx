<?php

namespace App\Filament\Resources\OrderLevels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class OrderLevelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('material-level.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('material-level.name'))
                    ->columnSpan(1),

                TextInput::make('sort_order')
                    ->label(__('material-level.sort_order'))
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(999)
                    ->placeholder('0')
                    ->columnSpan(1),

                ToggleButtons::make('is_visible')
                    ->label(__('material-level.is_visible'))
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

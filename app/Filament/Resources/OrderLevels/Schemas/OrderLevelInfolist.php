<?php

namespace App\Filament\Resources\OrderLevels\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderLevelInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('material-level.name'))
                    ->columnSpan(1),

                TextEntry::make('sort_order')
                    ->label(__('material-level.sort_order'))
                    ->badge()
                    ->color('success')
                    ->columnSpan(1),

                IconEntry::make('is_visible')
                    ->label(__('material-level.is_visible'))
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->columnSpan(1),

                TextEntry::make('created_at')
                    ->label(__('material-level.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->columnSpan(1),

                TextEntry::make('updated_at')
                    ->label(__('material-level.updated_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->columnSpan(1),
            ])->columns([
                'sm' => 2,
                'md' => 3,
                'lg' => 3,
                'xl' => 3,
                '2xl' => 3,
            ]);
    }
}

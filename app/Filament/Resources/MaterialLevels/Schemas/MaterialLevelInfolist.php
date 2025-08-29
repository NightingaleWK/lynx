<?php

namespace App\Filament\Resources\MaterialLevels\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class MaterialLevelInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('materiallevel.name'))
                    ->columnSpan(1),

                TextEntry::make('sort_order')
                    ->label(__('materiallevel.sort_order'))
                    ->badge()
                    ->color('success')
                    ->columnSpan(1),

                IconEntry::make('is_visible')
                    ->label(__('materiallevel.is_visible'))
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->columnSpan(1),

                TextEntry::make('created_at')
                    ->label(__('materiallevel.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->columnSpan(1),

                TextEntry::make('updated_at')
                    ->label(__('materiallevel.updated_at'))
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

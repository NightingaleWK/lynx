<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class MaterialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('material.name'))
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextEntry::make('en_name')
                    ->label(__('material.en_name'))
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextEntry::make('alias')
                    ->label(__('material.alias'))
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextEntry::make('materialLevel.name')
                    ->label(__('material.material_level'))
                    ->badge()
                    ->placeholder('未分类')
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextEntry::make('units.name')
                    ->label('单位')
                    ->getStateUsing(fn($record) => $record->units->pluck('name')->join(','))
                    ->badge()
                    ->separator(',')
                    ->placeholder('未关联')
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextEntry::make('description')
                    ->label(__('material.description'))
                    ->markdown()
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ]),

                TextEntry::make('created_at')
                    ->label(__('material.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextEntry::make('updated_at')
                    ->label(__('material.updated_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),
            ])
            ->columns([
                'sm' => 3,
                'md' => 3,
                'lg' => 3,
                'xl' => 3,
                '2xl' => 3,
            ]);
    }
}

<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class UnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('unit.name'))
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextEntry::make('en_name')
                    ->label(__('unit.en_name'))
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextEntry::make('remark')
                    ->label(__('unit.remark'))
                    ->markdown()
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ])
                    ->placeholder('无'),

                TextEntry::make('created_at')
                    ->label(__('unit.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextEntry::make('updated_at')
                    ->label(__('unit.updated_at'))
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

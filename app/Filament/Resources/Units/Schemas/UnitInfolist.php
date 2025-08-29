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
                Fieldset::make(__('unit.label'))
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('unit.name')),

                        TextEntry::make('en_name')
                            ->label(__('unit.en_name')),

                        TextEntry::make('remark')
                            ->label(__('unit.remark'))
                            ->markdown(),

                        TextEntry::make('created_at')
                            ->label(__('unit.created_at'))
                            ->dateTime('Y-m-d H:i:s'),

                        TextEntry::make('updated_at')
                            ->label(__('unit.updated_at'))
                            ->dateTime('Y-m-d H:i:s'),
                    ]),
            ])
            ->columns([
                'sm' => 2,
                'md' => 2,
                'lg' => 2,
                'xl' => 2,
                '2xl' => 2,
            ]);
    }
}

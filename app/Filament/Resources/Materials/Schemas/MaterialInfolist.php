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
                Fieldset::make(__('material.label'))
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
                            ->label(__('material.name')),

                        TextEntry::make('en_name')
                            ->label(__('material.en_name')),

                        TextEntry::make('alias')
                            ->label(__('material.alias')),

                        TextEntry::make('description')
                            ->label(__('material.description'))
                            ->markdown(),

                        TextEntry::make('created_at')
                            ->label(__('material.created_at'))
                            ->dateTime('Y-m-d H:i:s'),

                        TextEntry::make('updated_at')
                            ->label(__('material.updated_at'))
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

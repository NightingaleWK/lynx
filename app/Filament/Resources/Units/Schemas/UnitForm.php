<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('unit.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('unit.name'))
                    ->columnSpan(1),

                TextInput::make('en_name')
                    ->label(__('unit.en_name'))
                    ->maxLength(100)
                    ->placeholder(__('unit.en_name'))
                    ->columnSpan(1),

                Textarea::make('remark')
                    ->label(__('unit.remark'))
                    ->rows(3)
                    ->placeholder(__('unit.remark'))
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
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

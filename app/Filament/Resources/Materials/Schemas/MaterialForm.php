<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('material.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('material.name'))
                    ->columnSpan(1),

                TextInput::make('en_name')
                    ->label(__('material.en_name'))
                    ->maxLength(100)
                    ->placeholder(__('material.en_name'))
                    ->columnSpan(1),

                TextInput::make('alias')
                    ->label(__('material.alias'))
                    ->maxLength(255)
                    ->placeholder(__('material.alias'))
                    ->columnSpan(1),

                Textarea::make('description')
                    ->label(__('material.description'))
                    ->rows(3)
                    ->placeholder(__('material.description'))
                    ->columnSpanFull(),
            ])
            ->columns([
                'sm' => 2,
                'md' => 2,
                'lg' => 6,
                'xl' => 6,
                '2xl' => 6,
            ]);
    }
}

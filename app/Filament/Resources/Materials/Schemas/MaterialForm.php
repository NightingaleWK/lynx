<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
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
                    ->columnSpan(1)
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 3,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextInput::make('en_name')
                    ->label(__('material.en_name'))
                    ->maxLength(100)
                    ->placeholder(__('material.en_name'))
                    ->columnSpan(1)
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                TextInput::make('alias')
                    ->label(__('material.alias'))
                    ->maxLength(255)
                    ->placeholder(__('material.alias'))
                    ->columnSpan(1)
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                Select::make('units')
                    ->label('关联单位')
                    ->relationship(titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->columnSpan([
                        'sm' => 3,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                        '2xl' => 1,
                    ]),

                Textarea::make('description')
                    ->label(__('material.description'))
                    ->autosize()
                    ->placeholder(__('material.description'))
                    ->columnSpan([
                        'sm' => 3,
                        'md' => 3,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ]),
            ])
            ->columns([
                'sm' => 3,
                'md' => 2,
                'lg' => 3,
                'xl' => 3,
                '2xl' => 3,
            ]);
    }
}

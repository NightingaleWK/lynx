<?php

namespace App\Filament\Resources\Dishes\Schemas;

use App\Models\Ingredient;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DishForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')->required(),
                    ])
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),

                Repeater::make('dishIngredients')
                    ->relationship('dishIngredients')
                    ->schema([
                        Select::make('ingredient_id')
                            ->label('Ingredient')
                            ->options(Ingredient::all()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $unit = Ingredient::find($state)?->base_unit;
                                $set('unit', $unit);
                            }),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        TextInput::make('unit')
                            ->required()
                            ->readOnly(),
                        TextInput::make('remark')
                            ->placeholder('e.g. 2勺'),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),

                DateTimePicker::make('last_eaten_at'),
                TextInput::make('frequency')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

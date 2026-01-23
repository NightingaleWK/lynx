<?php

namespace App\Filament\Resources\IngredientAisles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class IngredientAisleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

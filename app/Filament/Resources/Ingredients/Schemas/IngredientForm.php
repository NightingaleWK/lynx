<?php

namespace App\Filament\Resources\Ingredients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class IngredientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('aisle_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('base_unit')
                    ->required(),
            ]);
    }
}

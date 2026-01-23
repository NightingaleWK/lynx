<?php

namespace App\Filament\Resources\Ingredients\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class IngredientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('aisle_id')
                    ->numeric(),
                TextEntry::make('name'),
                TextEntry::make('base_unit'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

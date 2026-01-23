<?php

namespace App\Filament\Resources\IngredientAisles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class IngredientAisleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

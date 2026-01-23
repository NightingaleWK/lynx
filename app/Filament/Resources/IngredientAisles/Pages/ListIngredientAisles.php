<?php

namespace App\Filament\Resources\IngredientAisles\Pages;

use App\Filament\Resources\IngredientAisles\IngredientAisleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIngredientAisles extends ListRecords
{
    protected static string $resource = IngredientAisleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

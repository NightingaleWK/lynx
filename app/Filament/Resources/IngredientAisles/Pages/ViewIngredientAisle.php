<?php

namespace App\Filament\Resources\IngredientAisles\Pages;

use App\Filament\Resources\IngredientAisles\IngredientAisleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewIngredientAisle extends ViewRecord
{
    protected static string $resource = IngredientAisleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

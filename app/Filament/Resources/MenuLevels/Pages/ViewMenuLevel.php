<?php

namespace App\Filament\Resources\MenuLevels\Pages;

use App\Filament\Resources\MenuLevels\MenuLevelResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMenuLevel extends ViewRecord
{
    protected static string $resource = MenuLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

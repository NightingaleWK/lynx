<?php

namespace App\Filament\Resources\MaterialLevels\Pages;

use App\Filament\Resources\MaterialLevels\MaterialLevelResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMaterialLevel extends ViewRecord
{
    protected static string $resource = MaterialLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

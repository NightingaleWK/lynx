<?php

namespace App\Filament\Resources\MaterialLevels\Pages;

use App\Filament\Resources\MaterialLevels\MaterialLevelResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMaterialLevel extends EditRecord
{
    protected static string $resource = MaterialLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

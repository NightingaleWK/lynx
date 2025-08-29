<?php

namespace App\Filament\Resources\MaterialLevels\Pages;

use App\Filament\Resources\MaterialLevels\MaterialLevelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaterialLevels extends ListRecords
{
    protected static string $resource = MaterialLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

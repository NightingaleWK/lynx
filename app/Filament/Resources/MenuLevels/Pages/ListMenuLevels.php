<?php

namespace App\Filament\Resources\MenuLevels\Pages;

use App\Filament\Resources\MenuLevels\MenuLevelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMenuLevels extends ListRecords
{
    protected static string $resource = MenuLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

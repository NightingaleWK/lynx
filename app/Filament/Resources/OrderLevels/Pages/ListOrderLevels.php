<?php

namespace App\Filament\Resources\OrderLevels\Pages;

use App\Filament\Resources\OrderLevels\OrderLevelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrderLevels extends ListRecords
{
    protected static string $resource = OrderLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

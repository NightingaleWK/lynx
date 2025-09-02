<?php

namespace App\Filament\Resources\OrderLevels\Pages;

use App\Filament\Resources\OrderLevels\OrderLevelResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrderLevel extends ViewRecord
{
    protected static string $resource = OrderLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

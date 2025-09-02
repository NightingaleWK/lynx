<?php

namespace App\Filament\Resources\OrderLevels\Pages;

use App\Filament\Resources\OrderLevels\OrderLevelResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrderLevel extends EditRecord
{
    protected static string $resource = OrderLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

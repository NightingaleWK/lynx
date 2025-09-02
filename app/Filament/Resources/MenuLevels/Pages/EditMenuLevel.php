<?php

namespace App\Filament\Resources\MenuLevels\Pages;

use App\Filament\Resources\MenuLevels\MenuLevelResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMenuLevel extends EditRecord
{
    protected static string $resource = MenuLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

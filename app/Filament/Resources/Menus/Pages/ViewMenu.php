<?php

namespace App\Filament\Resources\Menus\Pages;

use App\Filament\Resources\Menus\MenuResource;
use App\Models\Menu;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMenu extends ViewRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function resolveRecord(int|string $key): Menu
    {
        return Menu::with(['menuMaterials.material', 'menuMaterials.unit', 'menuLevel'])
            ->findOrFail($key);
    }
}

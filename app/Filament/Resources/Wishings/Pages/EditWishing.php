<?php

namespace App\Filament\Resources\Wishings\Pages;

use App\Filament\Resources\Wishings\WishingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWishing extends EditRecord
{
    protected static string $resource = WishingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

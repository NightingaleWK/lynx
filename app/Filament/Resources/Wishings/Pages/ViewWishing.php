<?php

namespace App\Filament\Resources\Wishings\Pages;

use App\Filament\Resources\Wishings\WishingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWishing extends ViewRecord
{
    protected static string $resource = WishingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\Wishings\Pages;

use App\Filament\Resources\Wishings\WishingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWishings extends ListRecords
{
    protected static string $resource = WishingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

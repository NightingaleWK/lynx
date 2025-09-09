<?php

namespace App\Filament\Resources\Wishings;

use App\Filament\Resources\Wishings\Pages\CreateWishing;
use App\Filament\Resources\Wishings\Pages\EditWishing;
use App\Filament\Resources\Wishings\Pages\ListWishings;
use App\Filament\Resources\Wishings\Pages\ViewWishing;
use App\Filament\Resources\Wishings\Schemas\WishingForm;
use App\Filament\Resources\Wishings\Schemas\WishingInfolist;
use App\Filament\Resources\Wishings\Tables\WishingsTable;
use App\Models\Wishing;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WishingResource extends Resource
{
    protected static ?string $model = Wishing::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $recordTitleAttribute = 'content';

    protected static string | UnitEnum | null $navigationGroup = '许愿池';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('wishing.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('wishing.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('wishing.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return WishingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WishingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WishingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWishings::route('/'),
            'create' => CreateWishing::route('/create'),
            // 'view' => ViewWishing::route('/{record}'),
            // 'edit' => EditWishing::route('/{record}/edit'),
        ];
    }
}

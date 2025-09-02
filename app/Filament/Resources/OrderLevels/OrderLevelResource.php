<?php

namespace App\Filament\Resources\OrderLevels;

use App\Filament\Resources\OrderLevels\Pages\CreateOrderLevel;
use App\Filament\Resources\OrderLevels\Pages\EditOrderLevel;
use App\Filament\Resources\OrderLevels\Pages\ListOrderLevels;
use App\Filament\Resources\OrderLevels\Pages\ViewOrderLevel;
use App\Filament\Resources\OrderLevels\Schemas\OrderLevelForm;
use App\Filament\Resources\OrderLevels\Schemas\OrderLevelInfolist;
use App\Filament\Resources\OrderLevels\Tables\OrderLevelsTable;
use App\Models\OrderLevel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OrderLevelResource extends Resource
{
    protected static ?string $model = OrderLevel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = '订单管理';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('order-level.label');
    }

    public static function form(Schema $schema): Schema
    {
        return OrderLevelForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderLevelInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderLevelsTable::configure($table);
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
            'index' => ListOrderLevels::route('/'),
            'create' => CreateOrderLevel::route('/create'),
            // 'view' => ViewOrderLevel::route('/{record}'),
            // 'edit' => EditOrderLevel::route('/{record}/edit'),
        ];
    }
}

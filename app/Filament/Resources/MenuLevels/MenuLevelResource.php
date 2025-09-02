<?php

namespace App\Filament\Resources\MenuLevels;

use App\Filament\Resources\MenuLevels\Pages\CreateMenuLevel;
use App\Filament\Resources\MenuLevels\Pages\EditMenuLevel;
use App\Filament\Resources\MenuLevels\Pages\ListMenuLevels;
use App\Filament\Resources\MenuLevels\Pages\ViewMenuLevel;
use App\Filament\Resources\MenuLevels\Schemas\MenuLevelForm;
use App\Filament\Resources\MenuLevels\Schemas\MenuLevelInfolist;
use App\Filament\Resources\MenuLevels\Tables\MenuLevelsTable;
use App\Models\MenuLevel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MenuLevelResource extends Resource
{
    protected static ?string $model = MenuLevel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = '菜谱管理';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('menu-level.label');
    }

    public static function form(Schema $schema): Schema
    {
        return MenuLevelForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MenuLevelInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenuLevelsTable::configure($table);
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
            'index' => ListMenuLevels::route('/'),
            'create' => CreateMenuLevel::route('/create'),
            // 'view' => ViewMenuLevel::route('/{record}'),
            // 'edit' => EditMenuLevel::route('/{record}/edit'),
        ];
    }
}

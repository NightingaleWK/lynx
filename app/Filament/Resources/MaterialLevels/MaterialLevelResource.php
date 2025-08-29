<?php

namespace App\Filament\Resources\MaterialLevels;

use App\Filament\Resources\MaterialLevels\Pages\CreateMaterialLevel;
use App\Filament\Resources\MaterialLevels\Pages\EditMaterialLevel;
use App\Filament\Resources\MaterialLevels\Pages\ListMaterialLevels;
use App\Filament\Resources\MaterialLevels\Pages\ViewMaterialLevel;
use App\Filament\Resources\MaterialLevels\Schemas\MaterialLevelForm;
use App\Filament\Resources\MaterialLevels\Schemas\MaterialLevelInfolist;
use App\Filament\Resources\MaterialLevels\Tables\MaterialLevelsTable;
use App\Models\MaterialLevel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MaterialLevelResource extends Resource
{
    protected static ?string $model = MaterialLevel::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = '物料管理';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('materiallevel.label');
    }

    public static function form(Schema $schema): Schema
    {
        return MaterialLevelForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MaterialLevelInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialLevelsTable::configure($table);
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
            'index' => ListMaterialLevels::route('/'),
            'create' => CreateMaterialLevel::route('/create'),
            // 'view' => ViewMaterialLevel::route('/{record}'),
            // 'edit' => EditMaterialLevel::route('/{record}/edit'),
        ];
    }
}

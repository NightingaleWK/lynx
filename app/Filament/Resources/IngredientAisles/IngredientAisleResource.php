<?php

namespace App\Filament\Resources\IngredientAisles;

use App\Filament\Resources\BaseResource;
use App\Filament\Resources\IngredientAisles\Pages\CreateIngredientAisle;
use App\Filament\Resources\IngredientAisles\Pages\EditIngredientAisle;
use App\Filament\Resources\IngredientAisles\Pages\ListIngredientAisles;
use App\Filament\Resources\IngredientAisles\Pages\ViewIngredientAisle;
use App\Filament\Resources\IngredientAisles\Schemas\IngredientAisleForm;
use App\Filament\Resources\IngredientAisles\Schemas\IngredientAisleInfolist;
use App\Filament\Resources\IngredientAisles\Tables\IngredientAislesTable;
use App\Models\IngredientAisle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class IngredientAisleResource extends BaseResource
{
    protected static ?string $model = IngredientAisle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return IngredientAisleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IngredientAisleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IngredientAislesTable::configure($table);
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
            'index' => ListIngredientAisles::route('/'),
            'create' => CreateIngredientAisle::route('/create'),
            'view' => ViewIngredientAisle::route('/{record}'),
            'edit' => EditIngredientAisle::route('/{record}/edit'),
        ];
    }
}

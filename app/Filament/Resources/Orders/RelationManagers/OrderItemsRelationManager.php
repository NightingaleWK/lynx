<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    protected static ?string $recordTitleAttribute = 'menu.title';

    protected static ?string $title = null;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('order-item.plural_label');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('menu_id')
                    ->label(__('order-item.menu'))
                    ->relationship('menu', 'title')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required(),
                TextInput::make('quantity')
                    ->label(__('order-item.quantity'))
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required(),
                TextInput::make('item_remarks')
                    ->label(__('order-item.item_remarks'))
                    ->placeholder(__('order-item.form.remarks_placeholder'))
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('menu.title')
            ->columns([
                TextColumn::make('menu.title')
                    ->label(__('order-item.menu_name'))
                    ->searchable(),
                TextColumn::make('menu.menuLevel.name')
                    ->label(__('order-item.menu_category'))
                    ->badge(),
                TextColumn::make('quantity')
                    ->label(__('order-item.quantity'))
                    ->suffix(__('order-item.unit_piece')),
                TextColumn::make('item_remarks')
                    ->label(__('order-item.item_remarks'))
                    ->limit(20)
                    ->placeholder(__('order-item.no_remarks')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('order-item.add_menu')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'asc');
    }
}

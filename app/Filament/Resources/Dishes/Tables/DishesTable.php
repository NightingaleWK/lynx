<?php

namespace App\Filament\Resources\Dishes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DishesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label(__('dish.fields.category_id'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable(),

                ImageColumn::make('images')
                    ->disk('public')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->placeholder('暂无图片'),

                TextColumn::make('last_eaten_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->placeholder('从未吃过')
                    ->sortable(),

                TextColumn::make('frequency')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Materials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('material.id'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label(__('material.name'))
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                TextColumn::make('en_name')
                    ->label(__('material.en_name'))
                    ->sortable()
                    ->searchable()
                    ->limit(20),

                TextColumn::make('alias')
                    ->label(__('material.alias'))
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                TextColumn::make('description')
                    ->label(__('material.description'))
                    ->wrap()
                    ->html(),

                TextColumn::make('created_at')
                    ->label(__('material.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('material.updated_at'))
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

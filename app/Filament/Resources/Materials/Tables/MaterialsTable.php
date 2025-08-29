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
                TextColumn::make('name')
                    ->label(__('material.name'))
                    ->searchable()
                    ->limit(30),

                TextColumn::make('en_name')
                    ->label(__('material.en_name'))
                    ->searchable()
                    ->limit(20)
                    ->placeholder('无'),

                TextColumn::make('alias')
                    ->label(__('material.alias'))
                    ->searchable()
                    ->limit(30)
                    ->placeholder('无'),

                TextColumn::make('materialLevel.name')
                    ->label(__('material.material_level'))
                    ->badge()
                    ->searchable()
                    ->limit(20)
                    ->placeholder('无'),

                TextColumn::make('units.name')
                    ->label('单位')
                    ->badge()
                    ->separator(',')
                    ->searchable()
                    ->limit(30)
                    ->placeholder('无'),

                TextColumn::make('description')
                    ->label(__('material.description'))
                    ->limit(50)
                    ->placeholder('无'),

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

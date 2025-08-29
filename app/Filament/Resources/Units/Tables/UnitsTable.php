<?php

namespace App\Filament\Resources\Units\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('unit.id'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label(__('unit.name'))
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                TextColumn::make('en_name')
                    ->label(__('unit.en_name'))
                    ->sortable()
                    ->searchable()
                    ->limit(20),

                TextColumn::make('remark')
                    ->label(__('unit.remark'))
                    ->limit(50)
                    ->html(),

                TextColumn::make('created_at')
                    ->label(__('unit.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('unit.updated_at'))
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

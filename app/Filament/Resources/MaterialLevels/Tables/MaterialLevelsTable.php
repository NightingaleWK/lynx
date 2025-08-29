<?php

namespace App\Filament\Resources\MaterialLevels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class MaterialLevelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('materiallevel.name'))
                    ->searchable()
                    ->limit(30),

                TextColumn::make('sort_order')
                    ->label(__('materiallevel.sort_order'))
                    ->numeric()
                    ->badge()
                    ->color('success'),

                ToggleColumn::make('is_visible')
                    ->label(__('materiallevel.is_visible'))
                    ->onColor('success')
                    ->onIcon(Heroicon::Check)
                    ->offIcon(Heroicon::XMark),

                TextColumn::make('created_at')
                    ->label(__('materiallevel.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('materiallevel.updated_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'desc')
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

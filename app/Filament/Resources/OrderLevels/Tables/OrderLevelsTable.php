<?php

namespace App\Filament\Resources\OrderLevels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class OrderLevelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('order-level.name'))
                    ->searchable()
                    ->limit(30),

                TextColumn::make('sort_order')
                    ->label(__('order-level.sort_order'))
                    ->numeric()
                    ->badge()
                    ->color('success'),

                ToggleColumn::make('is_visible')
                    ->label(__('order-level.is_visible'))
                    ->onColor('success')
                    ->onIcon(Heroicon::Check)
                    ->offIcon(Heroicon::XMark),

                TextColumn::make('created_at')
                    ->label(__('order-level.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('order-level.updated_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'desc')->filters([
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

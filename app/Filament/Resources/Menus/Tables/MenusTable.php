<?php

namespace App\Filament\Resources\Menus\Tables;

use App\Models\MenuLevel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('menu.title'))
                    ->searchable()
                    ->limit(30)
                    ->weight('bold'),

                TextColumn::make('subtitle')
                    ->label(__('menu.subtitle'))
                    ->searchable()
                    ->limit(25)
                    ->placeholder('无')
                    ->color('gray'),

                TextColumn::make('menuLevel.name')
                    ->label(__('menu.menu_level'))
                    ->badge()
                    ->searchable()
                    ->limit(20)
                    ->placeholder('无分类')
                    ->color('info'),

                TextColumn::make('order_count')
                    ->label(__('menu.order_count'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->icon(Heroicon::ShoppingCart),

                TextColumn::make('view_count')
                    ->label(__('menu.view_count'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->icon(Heroicon::Eye),

                TextColumn::make('sort_order')
                    ->label(__('menu.sort_order'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                ToggleColumn::make('is_visible')
                    ->label(__('menu.is_visible'))
                    ->onColor('success')
                    ->onIcon(Heroicon::Check)
                    ->offIcon(Heroicon::XMark)
                    ->offColor('danger'),

                TextColumn::make('content')
                    ->label(__('menu.content'))
                    ->limit(50)
                    ->placeholder('无内容')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('menu.created_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('menu.updated_at'))
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'desc')
            ->filters([
                SelectFilter::make('menu_level_id')
                    ->label(__('menu.menu_level'))
                    ->options(MenuLevel::pluck('name', 'id'))
                    ->searchable(),

                TernaryFilter::make('is_visible')
                    ->label(__('menu.is_visible'))
                    ->placeholder('全部')
                    ->trueLabel(__('menu.visible'))
                    ->falseLabel(__('menu.hidden')),

                SelectFilter::make('order_count')
                    ->label(__('menu.order_count'))
                    ->options([
                        'high' => '高点击量 (50+)',
                        'medium' => '中等点击量 (10-49)',
                        'low' => '低点击量 (0-9)',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value']) {
                            'high' => $query->where('order_count', '>=', 50),
                            'medium' => $query->whereBetween('order_count', [10, 49]),
                            'low' => $query->where('order_count', '<', 10),
                            default => $query,
                        };
                    }),
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

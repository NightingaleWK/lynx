<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label(__('order.order_number'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('dining_time')
                    ->label(__('order.dining_time'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                SelectColumn::make('status')
                    ->label(__('order.status'))
                    ->options(Order::getStatusOptions())
                    ->native(false)
                    ->selectablePlaceholder(false),

                TextColumn::make('orderItems_summary')
                    ->label(__('order.menu_details'))
                    ->getStateUsing(function ($record) {
                        $items = $record->orderItems->map(function ($item) {
                            return $item->menu->title . ' × ' . $item->quantity;
                        });
                        return $items->toArray();
                    })
                    ->listWithLineBreaks()
                    ->limitList(5)
                    ->expandableLimitedList(),

                TextColumn::make('orderItems_count')
                    ->label(__('order.total_quantity'))
                    ->getStateUsing(function ($record) {
                        return $record->orderItems->sum('quantity');
                    })
                    ->suffix(__('order-item.unit_piece'))
                    ->badge()
                    ->color('primary'),

                TextColumn::make('remarks')
                    ->label(__('order.remarks'))
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    })
                    ->placeholder('无'),

                TextColumn::make('created_at')
                    ->label(__('order.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('order.status'))
                    ->native(false)
                    ->multiple()
                    ->options(Order::getStatusOptions()),

                Filter::make('dining_date')
                    ->label(__('order.dining_date'))
                    ->schema([
                        DatePicker::make('date')
                            ->label(__('order.select_date'))
                            ->displayFormat('Y-m-d')
                            ->default(today())
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('dining_time', $date),
                            );
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('dining_time', 'desc');
    }
}

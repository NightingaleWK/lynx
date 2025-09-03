<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = null;

    protected function getHeading(): string
    {
        return __('widgets.latest_orders');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with('orderItems.menu')
                    ->latest()
                    ->limit(10)
            )
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

                TextColumn::make('status')
                    ->label(__('order.status'))
                    ->formatStateUsing(fn(string $state): string => Order::getStatusOptionsText()[$state] ?? $state)
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Order::STATUS_PENDING => 'warning',
                        Order::STATUS_CONFIRMED => 'info',
                        Order::STATUS_COOKING => 'primary',
                        Order::STATUS_COMPLETED => 'success',
                        Order::STATUS_CANCELLED => 'danger',
                        default => 'gray',
                    }),

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
            ->defaultSort('dining_time', 'desc')
            ->paginated(false);
    }
}

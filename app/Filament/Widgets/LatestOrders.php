<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
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
                    ->with('orderItems')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label(__('order.order_number'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('dining_time')
                    ->label(__('order.dining_time'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('order.status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Order::STATUS_PENDING => 'warning',
                        Order::STATUS_CONFIRMED => 'info',
                        Order::STATUS_COOKING => 'primary',
                        Order::STATUS_COMPLETED => 'success',
                        Order::STATUS_CANCELLED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => Order::getStatusOptionsText()[$state] ?? $state),

                TextColumn::make('orderItems_count')
                    ->label(__('order.items_count'))
                    ->getStateUsing(function ($record) {
                        return $record->orderItems->sum('quantity');
                    })
                    ->suffix(' 道')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label(__('order.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}

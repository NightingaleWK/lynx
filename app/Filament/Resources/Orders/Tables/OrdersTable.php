<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'served' => 'info',
                        'completed' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('meal_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('meal_period')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->searchable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label(__('Dishes')),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => __('Pending'),
                        'processing' => __('Processing'),
                        'served' => __('Served'),
                        'completed' => __('Completed'),
                    ]),
                Tables\Filters\SelectFilter::make('meal_period')
                    ->options([
                        'lunch' => 'Lunch',
                        'dinner' => 'Dinner',
                        'snack' => 'Snack',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('advance_status')
                        ->label('Advance Status')
                        ->icon('heroicon-m-arrow-right-circle')
                        ->color('primary')
                        ->action(function (Order $record) {
                            $nextStatus = match ($record->status) {
                                'pending' => 'processing',
                                'processing' => 'served',
                                'served' => 'completed',
                                default => null,
                            };

                            if ($nextStatus) {
                                $record->update(['status' => $nextStatus]);
                            }
                        })
                        ->visible(fn (Order $record) => in_array($record->status, ['pending', 'processing', 'served']) && auth()->user()->can('update_order')),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('shopping_list')
                        ->label(__('Generate Shopping List'))
                        ->icon('heroicon-o-shopping-cart')
                        ->action(fn (Collection $records) => redirect()->route('filament.admin.pages.shopping-list', ['orders' => $records->pluck('id')->implode(',')]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

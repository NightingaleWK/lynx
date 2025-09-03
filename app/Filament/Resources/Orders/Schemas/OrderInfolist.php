<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make(__('order.form.basic_info'))
                    ->schema([
                        // TextEntry::make('order_number')
                        //     ->label(__('order.order_number'))
                        //     ->size('lg')
                        //     ->weight('bold')
                        //     ->copyable()
                        //     ->icon('heroicon-o-hashtag')
                        //     ->columnSpan([
                        //         'sm' => 1,
                        //         'md' => 1,
                        //         'lg' => 1,
                        //         'xl' => 1,
                        //         '2xl' => 1,
                        //     ]),

                        TextEntry::make('dining_time')
                            ->label(__('order.dining_time'))
                            ->dateTime('Y-m-d H:i')
                            ->icon('heroicon-o-clock')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                                '2xl' => 1,
                            ]),

                        TextEntry::make('status')
                            ->label(__('order.status'))
                            ->formatStateUsing(fn(string $state): string => \App\Models\Order::getStatusOptions()[$state] ?? $state)
                            ->badge()
                            ->colors([
                                'gray' => \App\Models\Order::STATUS_PENDING,
                                'primary' => \App\Models\Order::STATUS_CONFIRMED,
                                'info' => \App\Models\Order::STATUS_COOKING,
                                'success' => \App\Models\Order::STATUS_COMPLETED,
                                'danger' => \App\Models\Order::STATUS_CANCELLED,
                            ])
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                                '2xl' => 1,
                            ]),

                        TextEntry::make('remarks')
                            ->label(__('order.remarks'))
                            ->placeholder(__('order-item.no_remarks'))
                            ->icon('heroicon-o-chat-bubble-left-ellipsis')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                                '2xl' => 1,
                            ]),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 3,
                        'lg' => 3,
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->columnSpanFull(),

                Fieldset::make(__('order.form.order_items'))
                    ->schema([
                        RepeatableEntry::make('orderItems')
                            ->label(__('order.form.menu_list'))
                            ->schema([
                                TextEntry::make('menu.title')
                                    ->label(__('order-item.menu_name'))
                                    ->weight('bold')
                                    ->icon('heroicon-o-squares-2x2'),

                                TextEntry::make('menu.menuLevel.name')
                                    ->label(__('order-item.menu_category'))
                                    ->badge()
                                    ->color('primary'),

                                TextEntry::make('quantity')
                                    ->label(__('order-item.quantity'))
                                    ->numeric()
                                    ->suffix(__('order-item.unit_piece'))
                                    ->badge()
                                    ->color('success'),

                                TextEntry::make('item_remarks')
                                    ->label(__('order-item.item_remarks'))
                                    ->placeholder(__('order-item.no_remarks'))
                                    ->icon('heroicon-o-chat-bubble-left'),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),

                        TextEntry::make('total_quantity')
                            ->label(__('order.total_quantity'))
                            ->state(function ($record) {
                                return $record->orderItems->sum('quantity');
                            })
                            ->numeric()
                            ->suffix(__('order-item.unit_piece'))
                            ->badge()
                            ->color('primary')
                            ->icon('heroicon-o-calculator')
                            ->columnSpan(1),

                        TextEntry::make('total_items')
                            ->label(__('order.items_count'))
                            ->state(function ($record) {
                                return $record->orderItems->count();
                            })
                            ->numeric()
                            ->suffix(__('order-item.unit_item'))
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-list-bullet')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Fieldset::make('时间信息')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('order.created_at'))
                            ->dateTime('Y-m-d H:i:s')
                            ->icon('heroicon-o-calendar')
                            ->columnSpan(1),

                        TextEntry::make('updated_at')
                            ->label(__('order.updated_at'))
                            ->dateTime('Y-m-d H:i:s')
                            ->icon('heroicon-o-clock')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ])
            ->columns([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
                'xl' => 1,
                '2xl' => 1,
            ]);
    }
}

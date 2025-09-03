<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('order.form.basic_info'))
                    ->schema([
                        TextInput::make('order_number')
                            ->label(__('order.order_number'))
                            ->default(fn() => Order::generateOrderNumber())
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        DateTimePicker::make('dining_time')
                            ->label(__('order.dining_time'))
                            ->required()
                            ->displayFormat('Y-m-d H:i')
                            ->seconds(false)
                            ->native(false)
                            ->default(now()->addHour()),

                        ToggleButtons::make('status')
                            ->label(__('order.status'))
                            ->options(Order::getStatusOptions())
                            ->default(Order::STATUS_PENDING)
                            ->inline()
                            ->colors([
                                Order::STATUS_PENDING => 'gray',
                                Order::STATUS_CONFIRMED => 'primary',
                                Order::STATUS_COOKING => 'info',
                                Order::STATUS_COMPLETED => 'success',
                                Order::STATUS_CANCELLED => 'danger',
                            ])
                            ->columnSpanFull()
                            ->required(),

                        Textarea::make('remarks')
                            ->label(__('order.remarks'))
                            ->placeholder(__('order.form.remarks_placeholder'))
                            ->columnSpanFull()
                            ->rows(3),
                    ])
                    ->columns(2),

                Section::make(__('order.form.order_items'))
                    ->schema([
                        Repeater::make('orderItems')
                            ->label(__('order.form.menu_list'))
                            ->relationship()
                            ->schema([
                                Select::make('menu_id')
                                    ->label(__('order-item.menu'))
                                    ->relationship('menu', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required(),
                                TextInput::make('quantity')
                                    ->label(__('order-item.quantity'))
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),
                                TextInput::make('item_remarks')
                                    ->label(__('order-item.item_remarks'))
                                    ->placeholder(__('order-item.form.remarks_placeholder')),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel(__('order-item.add_menu'))
                            ->reorderable()
                    ])
            ]);
    }
}

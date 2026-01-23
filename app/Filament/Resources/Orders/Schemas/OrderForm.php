<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'served' => 'Served',
                        'completed' => 'Completed',
                    ])
                    ->required()
                    ->default('pending'),
                DatePicker::make('meal_date')
                    ->required(),
                Select::make('meal_period')
                    ->options([
                        'lunch' => 'Lunch',
                        'dinner' => 'Dinner',
                        'snack' => 'Snack',
                    ])
                    ->required(),
                Textarea::make('customer_note')
                    ->columnSpanFull(),
                Textarea::make('chef_note')
                    ->columnSpanFull(),

                Repeater::make('items')
                    ->relationship('items')
                    ->schema([
                        Select::make('dish_id')
                            ->relationship('dish', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        TextInput::make('note')
                            ->placeholder('e.g. less spicy'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}

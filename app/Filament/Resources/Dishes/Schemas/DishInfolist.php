<?php

namespace App\Filament\Resources\Dishes\Schemas;

use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Facades\Storage;

class DishInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Fieldset::make('基本信息')
                    ->columnSpan(2)
                    ->columns(5)
                    ->schema([
                        Grid::make()
                            ->columnSpan(3)
                            ->columns(4)
                            ->schema([
                                TextEntry::make('name')
                                    ->size(TextSize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->afterLabel(Schema::start(fn($record) => 'ID: ' . $record->id)),

                                TextEntry::make('category.name')
                                    ->label(__('dish.fields.category_id'))
                                    ->badge(),

                                TextEntry::make('description')
                                    ->placeholder('-')
                                    ->columnSpan(2),

                                TextEntry::make('last_eaten_at')
                                    ->dateTime()
                                    ->placeholder('-'),

                                TextEntry::make('frequency')
                                    ->numeric(),

                                TextEntry::make('created_at')
                                    ->dateTime('Y-m-d H:i:s')
                                    ->placeholder('-'),

                                TextEntry::make('updated_at')
                                    ->dateTime('Y-m-d H:i:s')
                                    ->placeholder('-'),
                            ]),

                        Grid::make()
                            ->columnSpan(2)
                            ->schema([
                                ImageEntry::make('images')
                                    ->disk('public')
                                    ->imageSize(100)
                                    ->square()
                                    ->columnSpan(2),
                            ]),
                    ]),

                Fieldset::make('菜谱信息')
                    ->columns(1)
                    ->schema([]),

                Fieldset::make('所需食材')
                    ->columns(1)
                    ->schema([
                        RepeatableEntry::make('dishIngredients')
                            ->label('详情')
                            ->table([
                                TableColumn::make(__('dish_ingredient.fields.ingredient_id'))
                                    ->markAsRequired(),

                                TableColumn::make(__('dish_ingredient.fields.quantity'))
                                    ->markAsRequired(),

                                TableColumn::make(__('dish_ingredient.fields.unit'))
                                    ->markAsRequired(),

                                TableColumn::make(__('dish_ingredient.fields.remark')),
                            ])
                            ->schema([
                                TextEntry::make('ingredient.name'),
                                TextEntry::make('quantity'),
                                TextEntry::make('unit'),
                                TextEntry::make('remark'),
                            ])
                    ]),

            ]);
    }
}

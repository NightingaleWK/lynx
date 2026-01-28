<?php

namespace App\Filament\Resources\Dishes\Schemas;

use App\Models\Ingredient;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;

class DishForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('基本信息')
                            ->columns(2)
                            ->schema([
                                Grid::make()
                                    ->columnSpan(1)
                                    ->columns(2)
                                    ->schema([
                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('name')->required(),
                                            ])
                                            ->required(),

                                        TextInput::make('name')
                                            ->required(),

                                        Textarea::make('description')
                                            ->columnSpanFull(),


                                        DateTimePicker::make('last_eaten_at')
                                            ->displayFormat('Y-m-d H:i:s')
                                            ->native(false),

                                        TextInput::make('frequency')
                                            ->required()
                                            ->numeric()
                                            ->default(0),
                                    ]),

                                Grid::make()
                                    ->columnSpan(1)
                                    ->columns(1)
                                    ->schema([
                                        FileUpload::make('images')
                                            ->label('菜品图片')
                                            ->image()
                                            ->multiple()
                                            ->imageEditor()
                                            ->disk('public')
                                            ->directory('dishes')
                                            ->visibility('public')
                                            ->reorderable()
                                            ->openable()
                                            ->downloadable()
                                            ->maxFiles(5)
                                            ->panelLayout('grid')
                                            ->helperText('最多上传 5 张图片，建议尺寸 800x600'),
                                    ])
                            ]),
                        Tab::make('菜谱')
                            ->schema([
                                MarkdownEditor::make('recipe')
                                    ->required()
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        [
                                            'bold',
                                            'italic',
                                            'strike',
                                            'link',
                                            'heading',
                                            'blockquote',
                                            'codeBlock',
                                            'bulletList',
                                            'orderedList',
                                            'table',
                                            // 'attachFiles',
                                            'undo',
                                            'redo'
                                        ],
                                    ]),
                            ]),
                        Tab::make('所需食材')
                            ->schema([
                                Repeater::make('dishIngredients')
                                    ->label('食材')
                                    ->table([
                                        TableColumn::make(__('dish_ingredient.fields.ingredient_id'))
                                            ->markAsRequired(),

                                        TableColumn::make(__('dish_ingredient.fields.quantity'))
                                            ->markAsRequired(),

                                        TableColumn::make(__('dish_ingredient.fields.unit'))
                                            ->markAsRequired(),

                                        TableColumn::make(__('dish_ingredient.fields.remark')),
                                    ])
                                    ->compact(true)
                                    ->relationship('dishIngredients')
                                    ->schema([
                                        Select::make('ingredient_id')
                                            ->options(Ingredient::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $unit = Ingredient::find($state)?->base_unit;
                                                $set('unit', $unit);
                                            }),

                                        TextInput::make('quantity')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),

                                        TextInput::make('unit')
                                            ->required()
                                            ->readOnly(),

                                        TextInput::make('remark')
                                            ->placeholder('e.g. 2勺'),
                                    ])
                                    ->columns(4)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}

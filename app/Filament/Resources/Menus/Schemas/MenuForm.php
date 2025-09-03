<?php

namespace App\Filament\Resources\Menus\Schemas;

use App\Models\MenuLevel;
use App\Models\Material;
use App\Models\Unit;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('基本信息')
                    ->schema([
                        TextInput::make('title')
                            ->label(__('menu.title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                                '2xl' => 2,
                            ]),

                        TextInput::make('subtitle')
                            ->label(__('menu.subtitle'))
                            ->maxLength(255)
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                                '2xl' => 2,
                            ]),

                        Select::make('menu_level_id')
                            ->label(__('menu.menu_level'))
                            ->options(MenuLevel::visible()->ordered()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                                '2xl' => 2,
                            ]),

                        MarkdownEditor::make('content')
                            ->label(__('menu.content'))
                            ->columnSpanFull(),
                    ])
                    ->columns([
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->columnSpanFull(),

                Section::make('物料清单')
                    ->schema([
                        Repeater::make('menu_materials')
                            ->label('物料列表')
                            ->schema([
                                Select::make('material_id')
                                    ->label('选择物料')
                                    ->options(Material::with('materialLevel')
                                        ->get()
                                        ->mapWithKeys(function ($material) {
                                            $levelName = $material->materialLevel?->name ?? '未分类';
                                            return [$material->id => "{$material->name} ({$levelName})"];
                                        }))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state) {
                                            $material = Material::find($state);
                                            if ($material && $material->units->count() > 0) {
                                                $set('unit_id', $material->units->first()->id);
                                            }
                                        }
                                    })
                                    ->columnSpan(1),

                                Select::make('unit_id')
                                    ->label('选择单位')
                                    ->options(function ($get) {
                                        $materialId = $get('material_id');
                                        if (!$materialId) {
                                            return [];
                                        }

                                        $material = Material::with('units')->find($materialId);
                                        return $material ? $material->units->pluck('name', 'id') : [];
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('quantity')
                                    ->label('数量')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->default(1)
                                    ->columnSpan(1),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->collapsible()
                            // ->itemLabel(fn(array $state): ?string => $state['material_id'] ?? null)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('统计信息')
                    ->schema([
                        TextInput::make('order_count')
                            ->label(__('menu.order_count'))
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('系统自动统计，不可手动修改')
                            ->columnSpan(1),

                        TextInput::make('view_count')
                            ->label(__('menu.view_count'))
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('系统自动统计，不可手动修改')
                            ->columnSpan(1),

                        TextInput::make('sort_order')
                            ->label(__('menu.sort_order'))
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(999)
                            ->placeholder('0')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('状态设置')
                    ->schema([
                        ToggleButtons::make('is_visible')
                            ->label(__('menu.is_visible'))
                            ->options([
                                true => '是',
                                false => '否'
                            ])
                            ->colors([
                                true => 'success',
                                false => 'danger',
                            ])
                            ->icons([
                                true => Heroicon::Check,
                                false => Heroicon::XMark,
                            ])
                            ->inline()
                            ->default(true)
                            ->belowLabel('✨设置为可见后，用户可以在小程序中查看此菜谱')
                            ->columnSpanFull(),
                    ])
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

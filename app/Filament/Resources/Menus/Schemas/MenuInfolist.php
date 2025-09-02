<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class MenuInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('基本信息')
                    ->schema([
                        TextEntry::make('title')
                            ->label(__('menu.title'))
                            ->size('lg')
                            ->weight('bold')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                                '2xl' => 1,
                            ]),

                        TextEntry::make('subtitle')
                            ->label(__('menu.subtitle'))
                            ->placeholder('无副标题')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                                '2xl' => 1,
                            ]),

                        TextEntry::make('menuLevel.name')
                            ->label(__('menu.menu_level'))
                            ->badge()
                            ->color('primary')
                            ->placeholder('未分类')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                                '2xl' => 1,
                            ]),

                        TextEntry::make('content')
                            ->label(__('menu.content'))
                            ->markdown()
                            ->placeholder('暂无内容')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 3,
                                'lg' => 3,
                                'xl' => 3,
                                '2xl' => 3,
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

                Fieldset::make('统计信息')
                    ->schema([
                        TextEntry::make('order_count')
                            ->label(__('menu.order_count'))
                            ->numeric()
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-shopping-cart')
                            ->columnSpan(1),

                        TextEntry::make('view_count')
                            ->label(__('menu.view_count'))
                            ->numeric()
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-eye')
                            ->columnSpan(1),

                        TextEntry::make('sort_order')
                            ->label(__('menu.sort_order'))
                            ->numeric()
                            ->badge()
                            ->color('warning')
                            ->icon('heroicon-o-arrows-up-down')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Fieldset::make('状态信息')
                    ->schema([
                        IconEntry::make('is_visible')
                            ->label(__('menu.is_visible'))
                            ->boolean()
                            ->trueIcon('heroicon-o-eye')
                            ->falseIcon('heroicon-o-eye-slash')
                            ->trueColor('success')
                            ->falseColor('danger')
                            ->columnSpan(1),

                        TextEntry::make('created_at')
                            ->label(__('menu.created_at'))
                            ->dateTime('Y-m-d H:i:s')
                            ->icon('heroicon-o-calendar')
                            ->columnSpan(1),

                        TextEntry::make('updated_at')
                            ->label(__('menu.updated_at'))
                            ->dateTime('Y-m-d H:i:s')
                            ->icon('heroicon-o-clock')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
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

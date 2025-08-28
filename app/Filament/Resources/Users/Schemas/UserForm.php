<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('用户信息')
                    ->schema([
                        TextInput::make('name')
                            ->label(__('user.name'))
                            ->required()
                            ->unique()
                            ->columnSpan(1),

                        TextInput::make('email')
                            ->label(__('user.email'))
                            ->email()
                            ->required()
                            ->unique()
                            ->columnSpan(1),

                        TextInput::make('password')
                            ->label(__('user.password'))
                            ->password()
                            ->placeholder(fn(string $context): string => $context === 'edit' ? __('user.password_edit_placeholder') : '')
                            ->required(fn(string $context): bool => $context === 'create')
                            ->dehydrated(fn($state) => filled($state))
                            ->dehydrateStateUsing(fn($state) => bcrypt($state))
                            ->autocomplete('new-password')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 4,
                        'xl' => 4,
                        '2xl' => 4,
                    ]),

                Section::make('操作')
                    ->schema([
                        ToggleButtons::make('is_active')
                            ->label(__('user.is_active'))
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
                            ->default(false)
                            ->belowLabel('✨激活方可登录小程序、使用各类功能')
                    ])
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ]),

            ])
            ->columns([
                'sm' => 2,
                'md' => 2,
                'lg' => 6,
                'xl' => 6,
                '2xl' => 6,
            ]);
    }
}

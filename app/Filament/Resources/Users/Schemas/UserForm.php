<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('user.name'))
                    ->required()
                    ->unique(),

                TextInput::make('email')
                    ->label(__('user.email'))
                    ->email()
                    ->required()
                    ->unique(),

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
            ->columns(2);
    }
}

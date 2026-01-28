<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

// use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->email()
                    ->required(),

                Select::make('roles')
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name'
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nick_name} {$record->name}")
                    ->multiple()
                    ->preload()
                    ->native(false),
            ]);
    }
}

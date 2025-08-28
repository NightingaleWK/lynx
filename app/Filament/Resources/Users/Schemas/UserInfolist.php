<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('user.name')),
                TextEntry::make('email')
                    ->label(__('user.email')),
                TextEntry::make('created_at')
                    ->label(__('user.created_at'))
                    ->dateTime('Y-m-d H:i:s'),
                TextEntry::make('updated_at')
                    ->label(__('user.updated_at'))
                    ->dateTime('Y-m-d H:i:s'),
            ]);
    }
}

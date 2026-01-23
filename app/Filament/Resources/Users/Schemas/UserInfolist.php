<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextEntry::make('name'),

                TextEntry::make('department.title')
                    ->label(__('user.fields.department_id'))
                    ->placeholder('-'),

                TextEntry::make('teams.title')
                    ->label(__('user.fields.teams'))
                    ->badge()
                    ->placeholder('-'),

                TextEntry::make('roles.nick_name')
                    ->label(__('user.fields.roles'))
                    ->badge()
                    ->placeholder('-'),

                TextEntry::make('email'),

                TextEntry::make('phone')
                    ->placeholder('-'),

                // TextEntry::make('email_verified_at')
                //     ->dateTime()
                //     ->placeholder('-'),

                ImageEntry::make('signature')
                    ->placeholder('暂无'),

                TextEntry::make('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->placeholder('-'),
            ]);
    }
}

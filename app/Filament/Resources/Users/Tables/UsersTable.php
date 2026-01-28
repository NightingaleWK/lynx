<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('email')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->wrap()
                    ->badge()
                    ->placeholder('-')
                    ->separator(','),

                TextColumn::make('created_at')
                    ->dateTime('Y-m-d h:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime('Y-m-d h:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([])
            ->recordActions([
                // 密码修改
                Action::make('改密')
                    ->icon('heroicon-o-key')
                    ->schema([
                        TextInput::make('password')->label(__('user.fields.password'))
                            ->required()
                            ->password(),
                    ])
                    ->action(function (array $data, User $record): void {
                        $record->password = $data['password'];
                        $record->save();

                        Notification::make()
                            ->title('密码修改成功')
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => Auth::user()->can('ChangePassword:User')),

                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Wishings\Tables;

use App\Models\Wishing;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WishingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('wishing.wisher_name'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('content')
                    ->label(__('wishing.content'))
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('wishing.status'))
                    ->formatStateUsing(fn(string $state): string => Wishing::getStatusOptions()[$state] ?? $state)
                    ->badge()
                    ->colors([
                        'gray' => Wishing::STATUS_PENDING,
                        'primary' => Wishing::STATUS_ACCEPTED,
                        'success' => Wishing::STATUS_FULFILLED,
                        'danger' => Wishing::STATUS_REJECTED,
                    ]),

                TextColumn::make('response')
                    ->label(__('wishing.response'))
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    })
                    ->placeholder('无回应')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('accepted_at')
                    ->label(__('wishing.accepted_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->placeholder('未受理')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('fulfilled_at')
                    ->label(__('wishing.fulfilled_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->placeholder('未实现')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('rejected_at')
                    ->label(__('wishing.rejected_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->placeholder('未抛弃')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('wishing.created_at'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('wishing.status'))
                    ->native(false)
                    ->multiple()
                    ->options(Wishing::getStatusOptions()),

                Filter::make('user')
                    ->label(__('wishing.by_wisher'))
                    ->form([
                        \Filament\Forms\Components\Select::make('user_id')
                            ->label(__('wishing.wisher_name'))
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['user_id'],
                                fn(Builder $query, $userId): Builder => $query->where('user_id', $userId),
                            );
                    }),

                Filter::make('created_at')
                    ->label(__('wishing.created_at'))
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')
                            ->label('开始日期'),
                        \Filament\Forms\Components\DatePicker::make('created_until')
                            ->label('结束日期'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                // 受理许愿操作
                Action::make('accept')
                    ->label(__('wishing.accept'))
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->visible(fn(Wishing $record): bool => $record->canBeAccepted())
                    ->form([
                        Textarea::make('response')
                            ->label(__('wishing.response'))
                            ->placeholder(__('wishing.form.response_placeholder'))
                            ->maxLength(500)
                            ->rows(3),
                    ])
                    ->action(function (Wishing $record, array $data): void {
                        $record->accept($data['response'] ?? null);

                        Notification::make()
                            ->title(__('wishing.notifications.accepted'))
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading(__('wishing.accept'))
                    ->modalDescription('确定要受理这个许愿吗？'),

                // 实现许愿操作
                Action::make('fulfill')
                    ->label(__('wishing.fulfill'))
                    ->icon('heroicon-o-gift')
                    ->color('success')
                    ->visible(fn(Wishing $record): bool => $record->canBeFulfilled())
                    ->action(function (Wishing $record): void {
                        $record->fulfill();

                        Notification::make()
                            ->title(__('wishing.notifications.fulfilled'))
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading(__('wishing.fulfill'))
                    ->modalDescription('确定要标记这个许愿为已实现吗？'),

                // 抛弃许愿操作
                Action::make('reject')
                    ->label(__('wishing.reject'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(Wishing $record): bool => $record->canBeRejected())
                    ->action(function (Wishing $record): void {
                        $record->reject();

                        Notification::make()
                            ->title(__('wishing.notifications.rejected'))
                            ->warning()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading(__('wishing.reject'))
                    ->modalDescription('确定要抛弃这个许愿吗？此操作不可撤销。'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn($query) => $query->with('user')->orderBy('created_at', 'desc'));
    }
}

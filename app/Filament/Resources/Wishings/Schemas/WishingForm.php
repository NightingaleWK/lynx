<?php

namespace App\Filament\Resources\Wishings\Schemas;

use App\Models\Wishing;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WishingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('wishing.form.basic_info'))
                    ->schema([
                        Select::make('user_id')
                            ->label(__('wishing.wisher_name'))
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->default(auth()->id())
                            ->required()
                            ->columnSpan(1),

                        Textarea::make('content')
                            ->label(__('wishing.content'))
                            ->placeholder(__('wishing.form.content_placeholder'))
                            ->required()
                            ->minLength(10)
                            ->maxLength(500)
                            ->rows(4)
                            ->columnSpanFull(),

                        ToggleButtons::make('status')
                            ->label(__('wishing.status'))
                            ->options(Wishing::getStatusOptions())
                            ->default(Wishing::STATUS_PENDING)
                            ->inline()
                            ->colors([
                                Wishing::STATUS_PENDING => 'gray',
                                Wishing::STATUS_ACCEPTED => 'primary',
                                Wishing::STATUS_FULFILLED => 'success',
                                Wishing::STATUS_REJECTED => 'danger',
                            ])
                            ->columnSpanFull()
                            ->required()
                            ->disabled(fn(?Wishing $record) => $record && !$record->canBeRejected()),

                        Textarea::make('response')
                            ->label(__('wishing.response'))
                            ->placeholder(__('wishing.form.response_placeholder'))
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull()
                            ->visible(fn(?Wishing $record) => $record && in_array($record->status, [Wishing::STATUS_ACCEPTED, Wishing::STATUS_FULFILLED, Wishing::STATUS_REJECTED])),
                    ])
                    ->columns(2),

                Section::make(__('wishing.form.wisher_info'))
                    ->schema([
                        TextInput::make('user.name')
                            ->label(__('wishing.wisher_name'))
                            ->disabled()
                            ->columnSpan(1),

                        Textarea::make('content')
                            ->label(__('wishing.content'))
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn(?Wishing $record) => $record && $record->exists),
            ]);
    }
}

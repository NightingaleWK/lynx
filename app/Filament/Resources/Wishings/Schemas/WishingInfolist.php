<?php

namespace App\Filament\Resources\Wishings\Schemas;

use App\Models\Wishing;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class WishingInfolist
{
    /**
     * 人性化时间格式化
     */
    private static function formatDuration(\DateInterval $duration): string
    {
        $parts = [];

        // 年
        if ($duration->y > 0) {
            $parts[] = $duration->y . '年';
        }

        // 月
        if ($duration->m > 0) {
            $parts[] = $duration->m . '个月';
        }

        // 天
        if ($duration->d > 0) {
            $parts[] = $duration->d . '天';
        }

        // 小时
        if ($duration->h > 0) {
            $parts[] = $duration->h . '小时';
        }

        // 分钟
        if ($duration->i > 0) {
            $parts[] = $duration->i . '分钟';
        }

        // 秒（只有在没有其他单位时才显示）
        if (empty($parts) && $duration->s > 0) {
            $parts[] = $duration->s . '秒';
        }

        // 如果所有时间都是0，显示"刚刚"
        if (empty($parts)) {
            return '刚刚';
        }

        // 最多显示2个时间单位
        return implode('', array_slice($parts, 0, 2));
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make(__('wishing.form.basic_info'))
                    ->schema([
                        TextEntry::make('user.name')
                            ->label(__('wishing.wisher_name'))
                            ->badge()
                            ->color('primary')
                            ->icon('heroicon-o-user')
                            ->columnSpan(1),

                        TextEntry::make('status')
                            ->label(__('wishing.status'))
                            ->formatStateUsing(fn(string $state): string => Wishing::getStatusOptions()[$state] ?? $state)
                            ->badge()
                            ->colors([
                                'gray' => Wishing::STATUS_PENDING,
                                'primary' => Wishing::STATUS_ACCEPTED,
                                'success' => Wishing::STATUS_FULFILLED,
                                'danger' => Wishing::STATUS_REJECTED,
                            ])
                            ->columnSpan(1),

                        TextEntry::make('created_at')
                            ->label(__('wishing.created_at'))
                            ->dateTime('Y-m-d H:i:s')
                            ->icon('heroicon-o-calendar')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Fieldset::make(__('wishing.form.wishing_content'))
                    ->schema([
                        TextEntry::make('content')
                            ->label(__('wishing.content'))
                            ->columnSpanFull()
                            ->markdown()
                            ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                    ])
                    ->columnSpanFull(),

                Fieldset::make(__('wishing.form.response_content'))
                    ->schema([
                        TextEntry::make('response')
                            ->label(__('wishing.response'))
                            ->placeholder('暂无回应')
                            ->columnSpanFull()
                            ->markdown()
                            ->icon('heroicon-o-chat-bubble-left')
                            ->visible(fn(Wishing $record) => $record->response),
                    ])
                    ->columnSpanFull()
                    ->visible(fn(Wishing $record) => $record->response),

                Fieldset::make('时间信息')
                    ->schema([
                        TextEntry::make('accepted_at')
                            ->label(__('wishing.accepted_at'))
                            ->dateTime('Y-m-d H:i:s')
                            ->icon('heroicon-o-check-circle')
                            ->placeholder('未受理')
                            ->columnSpan(1)
                            ->visible(fn(Wishing $record) => $record->accepted_at),

                        TextEntry::make('fulfilled_at')
                            ->label(__('wishing.fulfilled_at'))
                            ->dateTime('Y-m-d H:i:s')
                            ->icon('heroicon-o-gift')
                            ->placeholder('未实现')
                            ->columnSpan(1)
                            ->visible(fn(Wishing $record) => $record->fulfilled_at),

                        TextEntry::make('rejected_at')
                            ->label(__('wishing.rejected_at'))
                            ->dateTime('Y-m-d H:i:s')
                            ->icon('heroicon-o-x-circle')
                            ->placeholder('未抛弃')
                            ->columnSpan(1)
                            ->visible(fn(Wishing $record) => $record->rejected_at),

                        TextEntry::make('updated_at')
                            ->label(__('wishing.updated_at'))
                            ->dateTime('Y-m-d H:i:s')
                            ->icon('heroicon-o-clock')
                            ->columnSpan(1),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),

                Fieldset::make('状态统计')
                    ->schema([
                        TextEntry::make('status_duration')
                            ->label('处理时长')
                            ->state(function (Wishing $record) {
                                $startTime = $record->created_at;
                                $endTime = null;
                                $statusText = '';

                                // 根据状态确定结束时间和状态描述
                                switch ($record->status) {
                                    case Wishing::STATUS_FULFILLED:
                                        $endTime = $record->fulfilled_at;
                                        $statusText = '已实现';
                                        break;
                                    case Wishing::STATUS_REJECTED:
                                        $endTime = $record->rejected_at;
                                        $statusText = '已抛弃';
                                        break;
                                    default:
                                        // 待回应、已受理状态，计算到当前时间
                                        $endTime = now();
                                        $statusText = $record->status === Wishing::STATUS_ACCEPTED ? '已受理' : '待回应';
                                        break;
                                }

                                // 计算时长
                                $duration = $startTime->diff($endTime);
                                $durationText = self::formatDuration($duration);

                                return $statusText . '：' . $durationText;
                            })
                            ->badge()
                            ->color('gray')
                            ->icon('heroicon-o-clock')
                            ->columnSpan(1),
                    ])
                    ->columns(1)
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

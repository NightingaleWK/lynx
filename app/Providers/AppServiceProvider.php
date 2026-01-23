<?php

namespace App\Providers;

use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Tables\Columns\Column;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        // 重写 Filament\Forms\Components\Field::configureUsing()，实现自动翻译
        Field::configureUsing(function (Field $field) {
            $field->label(function () use ($field) {
                $fieldName = $field->getName();
                $model = $field->getModel();

                if (! $model) {
                    return null;
                }

                $modelName = Str::snake(class_basename($model));
                $key = "{$modelName}.fields.{$fieldName}";

                return Lang::has($key) ? __($key) : null;
            });
        });

        // 重写 Filament\Tables\Columns\Column::configureUsing()，实现自动翻译
        Column::configureUsing(function (Column $column) {
            $column->label(function () use ($column) {
                $columnName = $column->getName();
                $table = $column->getTable();

                if (! $table) {
                    return null;
                }

                $model = $table->getModel();

                if (! $model) {
                    return null;
                }

                $modelName = Str::snake(class_basename($model));
                $key = "{$modelName}.fields.{$columnName}";

                return Lang::has($key) ? __($key) : null;
            });
        });

        // 重写 Filament\Infolists\Components\Entry::configureUsing()，实现自动翻译
        Entry::configureUsing(function (Entry $entry) {
            $entry->label(function () use ($entry) {
                $entryName = $entry->getName();
                $componentContainer = $entry->getContainer();

                if (! $componentContainer) {
                    return null;
                }

                $model = $componentContainer->getModel();

                if (! $model) {
                    return null;
                }

                $modelName = Str::snake(class_basename($model));
                $key = "{$modelName}.fields.{$entryName}";

                return Lang::has($key) ? __($key) : null;
            });
        });
    }
}

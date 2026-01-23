<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

abstract class BaseResource extends Resource
{
    // 重写 Filament\Resources\Resource::getModelLabel()，实现自动翻译
    public static function getModelLabel(): string
    {
        $model = static::getModel();
        $modelName = Str::snake(class_basename($model));
        $key = "{$modelName}.model_label";

        return Lang::has($key) ? __($key) : parent::getModelLabel();
    }
}

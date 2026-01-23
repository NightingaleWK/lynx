<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum IngredientUnit: string implements HasLabel
{
    case Gram = 'g';
    case Milliliter = 'ml';
    case Piece = 'pc';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Gram => '克 (g)',
            self::Milliliter => '毫升 (ml)',
            self::Piece => '个/只/瓣',
        };
    }
}

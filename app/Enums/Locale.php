<?php

namespace App\Enums;

use function __;

enum Locale: string
{
    case En = 'en';
    case De = 'de';

    public function label(): string
    {
        return match ($this) {
            Locale::En => __('English'),
            Locale::De => __('German'),
        };
    }
}

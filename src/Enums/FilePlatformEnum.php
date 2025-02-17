<?php

namespace Niyama\FileManager\Enums;

enum FilePlatformEnum: string
{
    case DESKTOP = 'desktop';
    case MOBILE = 'mobile';

    public function label(): string
    {
        return match ($this) {
            self::DESKTOP => __('other.platform.desktop'),
            self::MOBILE => __('other.platform.mobile'),
        };
    }

}

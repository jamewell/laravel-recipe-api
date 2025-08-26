<?php

namespace App\Enums;

enum ImageMimeType: string
{
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';
    case WEBP = 'image/webp';
    case SVG = 'image/svg+xml';

    /** @return array<string> */
    public static function allowedTypes(): array
    {
        return [
            self::JPEG->value,
            self::PNG->value,
            self::GIF->value,
            self::WEBP->value,
        ];
    }

    /** @return array<string, array<string>> */
    public static function extensions(): array
    {
        return [
            self::JPEG->value => ['jpg', 'jpeg'],
            self::PNG->value => ['png'],
            self::GIF->value => ['gif'],
            self::WEBP->value => ['webp'],
            self::SVG->value => ['svg'],
        ];
    }

    public function getExtensions(): string
    {
        return match ($this) {
            self::JPEG => 'jpeg',
            self::PNG => 'png',
            self::GIF => 'gif',
            self::WEBP => 'webp',
            self::SVG => 'svg',
        };
    }
}

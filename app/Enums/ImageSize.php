<?php

namespace App\Enums;

enum ImageSize: int
{
    case ORIGINAL = 0;
    case LARGE = 1200;
    case MEDIUM = 800;
    case SMALL = 400;
    case THUMBNAIL = 200;

    /** @return array<string, int> */
    public static function getSizes(): array
    {
        return [
            'original' => self::ORIGINAL->value,
            'large' => self::LARGE->value,
            'medium' => self::MEDIUM->value,
            'small' => self::SMALL->value,
            'thumbnail' => self::THUMBNAIL->value,
        ];
    }

    public function getName(): string
    {
        return match ($this) {
            self::ORIGINAL => 'original',
            self::LARGE => 'large',
            self::MEDIUM => 'medium',
            self::SMALL => 'small',
            self::THUMBNAIL => 'thumbnail',
        };
    }

    public static function fromName(string $name): self
    {
        return match ($name) {
            'original' => self::ORIGINAL,
            'large' => self::LARGE,
            'medium' => self::MEDIUM,
            'small' => self::SMALL,
            'thumbnail' => self::THUMBNAIL,
            default => throw new \InvalidArgumentException("Invalid image size: {$name}"),
        };
    }
}

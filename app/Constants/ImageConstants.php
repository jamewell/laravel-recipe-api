<?php

namespace App\Constants;

class ImageConstants
{
    // File size limits
    public const MAX_FILE_SIZE_KB = 5120; // 5MB

    public const MAX_FILE_SIZE_BYTES = self::MAX_FILE_SIZE_KB * 1024;

    // Dimension constraints
    public const MIN_WIDTH = 100;

    public const MIN_HEIGHT = 100;

    public const MAX_WIDTH = 4000;

    public const MAX_HEIGHT = 4000;

    // Image quality
    public const DEFAULT_QUALITY = 80;

    public const THUMBNAIL_QUALITY = 75;

    // Storage paths
    public const RECIPE_IMAGE_DIRECTORY = 'recipes';

    public const FALLBACK_IMAGE_DIRECTORY = 'fallbacks';

    // Validation messages
    public const ERROR_INVALID_TYPE = 'Invalid image type. Allowed: JPEG, PNG, GIF, WEBP';

    public const ERROR_FILE_TOO_LARGE = 'Image too large. Maximum size: %dKB';

    public const ERROR_DIMENSIONS_TOO_SMALL = 'Image dimensions too small. Minimum: %dx%dpx';

    public const ERROR_DIMENSIONS_TOO_LARGE = 'Image dimensions too large. Maximum: %dx%dpx';
}

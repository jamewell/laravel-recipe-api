<?php

namespace App\Exceptions;

use App\Constants\ImageConstants;

abstract class ImageDimensionsException extends ImageValidationException
{
    public function __construct(string $message, string $userMessage, string $errorCode)
    {
        parent::__construct($message, $userMessage, $errorCode);
    }
}

class ImageTooSmallException extends ImageDimensionsException
{
    public function __construct()
    {
        parent::__construct(
            sprintf('Image dimensions too small. Minimum: %dx%dpx', ImageConstants::MIN_WIDTH, ImageConstants::MIN_HEIGHT),
            sprintf('The image is too small. Minimum dimensions are %dx%d pixels.', ImageConstants::MIN_WIDTH, ImageConstants::MIN_HEIGHT),
            'IMAGE_TOO_SMALL'
        );
    }
}

class ImageTooLargeException extends ImageDimensionsException
{
    public function __construct()
    {
        parent::__construct(
            sprintf('Image dimensions too large. Maximum: %dx%dpx', ImageConstants::MAX_WIDTH, ImageConstants::MAX_HEIGHT),
            sprintf('The image is too large. Maximum dimensions are %dx%d pixels.', ImageConstants::MAX_WIDTH, ImageConstants::MAX_HEIGHT),
            'IMAGE_TOO_LARGE_DIMENSIONS'
        );
    }
}

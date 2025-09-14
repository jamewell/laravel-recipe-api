<?php

namespace App\Exceptions;

use App\Constants\ImageConstants;

class ImageTooLargeException extends ImageValidationException
{
    public function __construct()
    {
        $maxSizeMB = ImageConstants::MAX_FILE_SIZE_KB / 1024;

        parent::__construct(
            sprintf(ImageConstants::ERROR_FILE_TOO_LARGE, ImageConstants::MAX_FILE_SIZE_KB),
            sprintf('The image is too large. Maximum file size is %.1fMB.', $maxSizeMB),
            'IMAGE_TOO_LARGE'
        );
    }
}

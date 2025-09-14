<?php

namespace App\Exceptions;

use App\Constants\ImageConstants;
use App\Enums\ImageMimeType;

class InvalidImageTypeException extends ImageValidationException
{
    public function __construct()
    {
        $allowedTypes = implode(', ', array_map(fn ($type) => strtoupper(explode('/', $type)[1]), ImageMimeType::allowedTypes()));

        parent::__construct(
            ImageConstants::ERROR_INVALID_TYPE,
            "Please upload a valid image file. Supported formats: {$allowedTypes}.",
            'INVALID_IMAGE_TYPE'
        );
    }
}

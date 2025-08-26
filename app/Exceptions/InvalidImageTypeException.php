<?php

namespace App\Exceptions;

use App\Enums\ImageMimeType;

class InvalidImageTypeException extends ImageValidationException
{
    public function __construct()
    {
        $allowedTypes = implode(', ', array_map(fn ($type) => strtoupper(explode('/', $type)[1]), ImageMimeType::allowedTypes()));

        parent::__construct(
            'Invalid image MIME type',
            "Please upload a valid image file. Supported formats: {$allowedTypes}.",
            'INVALID_IMAGE_TYPE'
        );
    }
}

<?php

namespace App\Exceptions;

class InvalidImageUploadException extends ImageValidationException
{
    public function __construct(string $message = 'The uploaded file is not valid')
    {
        parent::__construct(
            $message,
            'The uploaded image file is corrupted or invalid. Please try again.',
            'INVALID_IMAGE_UPLOAD'
        );
    }
}

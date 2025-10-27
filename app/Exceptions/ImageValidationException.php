<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class ImageValidationException extends ImageException
{
    public function __construct(
        string $message = '',
        string $userMessage = '',
        string $errorCode = 'IMAGE_VALIDATION_ERROR',
        int $code = Response::HTTP_UNPROCESSABLE_ENTITY
    ) {
        parent::__construct($message, $userMessage, $errorCode, $code);
    }
}

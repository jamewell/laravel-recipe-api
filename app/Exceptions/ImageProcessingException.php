<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class ImageProcessingException extends ImageException
{
    public function __construct(string $message = 'Failed to process image')
    {
        parent::__construct(
            $message,
            'We encountered an error while processing your image. Please try again or use a different image.',
            'IMAGE_PROCESSING_ERROR',
            Response::HTTP_INTERNAL_SERVER_ERROR,
        );
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Throwable;

abstract class ImageException extends Exception
{
    public function __construct(
        string $message = '',
        protected string $userMessage = '',
        protected string $errorCode = 'IMAGE_ERROR',
        int $code = 0,
        protected ?Throwable $previous = null,
    ) {
        $this->userMessage = $userMessage ?: $message;

        parent::__construct($message, $code, $previous);
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /** @return array<string, string> */
    public function toArray(): array
    {
        return [
            'error' => $this->getErrorCode(),
            'message' => $this->getUserMessage(),
            'detail' => $this->getMessage(),
        ];
    }
}

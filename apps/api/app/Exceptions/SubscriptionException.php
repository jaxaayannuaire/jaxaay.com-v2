<?php

namespace App\Exceptions;

use RuntimeException;

final class SubscriptionException extends RuntimeException
{
    public function __construct(
        public readonly string $errorCode,
        string $message,
        public readonly int $statusCode,
    ) {
        parent::__construct($message);
    }
}

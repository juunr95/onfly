<?php

namespace App\Exceptions;

use Exception;

class TravelWithNoRequester extends Exception
{
    const  MESSAGE = 'Travel must have a requester';

    public function __construct(string $message = self::MESSAGE, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render()
    {
        return response()->json([
            'error' => $this->message,
        ], 403);
    }
}

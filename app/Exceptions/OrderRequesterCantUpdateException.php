<?php

namespace App\Exceptions;

use Exception;

class OrderRequesterCantUpdateException extends Exception
{
    public function render()
    {
        return response()->json([
            'error' => 'Requester cannot update the order'
        ], 403);
    }
}

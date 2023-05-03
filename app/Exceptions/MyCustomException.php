<?php

namespace App\Exceptions;

use Exception;

class MyCustomException extends Exception
{
    public function render()
    {
        return response()->json(['message' => $this->getMessage()], 500);
    }
}

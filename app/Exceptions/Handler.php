<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
class Handler extends Exception
{
    protected function unauthenticated($request, array $guards)
    {
    return response()->json(['message' => 'Please login first'], 401);
    }


    
}

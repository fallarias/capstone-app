<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return null;  // Return null to avoid the redirect to login
        }
    }
    protected function unauthenticated($request, array $guards)
    {
        return response()->json(['message' => 'Please login first'], 401);
    }
}

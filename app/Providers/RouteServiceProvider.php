<?php

namespace App\Providers;


// use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public const HOME = '/home';

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();

        // Load the API routes with the `api` middleware
        Route::middleware('api')
             ->prefix('api')
             ->group(base_path('routes/api.php'));

        // Load the web routes with the `web` middleware
        Route::middleware('web')
             ->group(base_path('routes/web.php'));

        // Optional: Define a fallback route
        Route::fallback(function () {
            return response()->json(['message' => 'Not Found'], 404);
        });

    }
}

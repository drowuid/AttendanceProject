<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    // public const HOME = '/dashboard'; // Remove or comment out since we use method below

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }

    /**
     * Custom redirect based on user role after login.
     */
    public static function redirectTo()
    {
        $user = auth()->user();

        if ($user && $user->role === 'trainer') {
            return '/trainer/dashboard';
        }

        return '/trainee/dashboard';
    }
}

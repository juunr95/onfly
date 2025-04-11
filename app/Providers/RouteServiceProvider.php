<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->mapApiRoutes();
    }

    /**
     * Map API routes per version.
     */
    public function mapApiRoutes(): void
    {
        $routesPath = base_path('routes');

        foreach (glob("{$routesPath}/v*", GLOB_ONLYDIR) as $versionDirectory) {
            $version = basename($versionDirectory, '.php');
            $apiFile = "$routesPath/$version/api.php";

            if (file_exists($apiFile)) {
                Route::middleware('api')
                    ->prefix('api/' . $version)
                    ->group($apiFile);
            }
        }
    }
}

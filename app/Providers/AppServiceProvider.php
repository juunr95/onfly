<?php

namespace App\Providers;

use App\Events\TravelApproved;
use App\Events\TravelCancelled;
use App\Events\TravelCreated;
use App\Handlers\ApiHandler;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\TravelRepositoryInterface;
use App\Interfaces\TravelServiceInterface;
use App\Listeners\TravelApprovedListener;
use App\Listeners\TravelCreatedListener;
use App\Listeners\TravelCancelledListener;
use App\Repositories\OrderRepository;
use App\Repositories\TravelRepository;
use App\Services\OrderService;
use App\Services\TravelService;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(TravelServiceInterface::class, TravelService::class);
        $this->app->bind(TravelRepositoryInterface::class, TravelRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            TravelCreated::class,
            TravelCreatedListener::class
        );

        Event::listen(
            TravelCancelled::class,
            TravelCancelledListener::class,
        );

        Event::listen(
            TravelApproved::class,
            TravelApprovedListener::class,
        );
    }
}

<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Interface\Bus\CommandBusInterface;
use App\Buses\CommandBus;
use App\Contracts\Interface\Bus\EventBusInterface;
use App\Buses\EventBus;
use App\Contracts\Interface\Bus\QueryBusInterface;
use App\Buses\QueryBus;

final class BusServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: CommandBusInterface::class,
            concrete: CommandBus::class
        );

        $this->app->singleton(
            abstract: EventBusInterface::class,
            concrete: QueryBus::class
        );

        $this->app->singleton(
            abstract: QueryBusInterface::class,
            concrete: EventBus::class
        );
    }
}

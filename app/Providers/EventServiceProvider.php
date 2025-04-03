<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Modules\Account\Events\MediaWasUploaded;
use App\Modules\Account\Handlers\Write\UploadMediaHandler;
use App\Modules\Account\Events\MediaWasUpdated;
use App\Modules\Account\Handlers\Write\UpdateMediaHandler;
use App\Modules\Account\Events\MediaWasDeleted;
use App\Modules\Account\Handlers\Write\DeleteMediaHandler;
use Illuminate\Support\Facades\Event;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * Mapping of events and listeners.
     *
     * @var array
     */
    protected $listen = [
        MediaWasUploaded::class => [
            UploadMediaHandler::class,
        ],
        MediaWasUpdated::class => [
            UpdateMediaHandler::class,
        ],
        MediaWasDeleted::class => [
            DeleteMediaHandler::class,
        ],
    ];
    
    /**
     * Register any application services.
     */
    // public function register()
    // {
    //     $this->app->booted(
    //         callback: function (): void {
    //             $this->app->make(
    //                 abstract: EventBusInterface::class
    //             )->register(
    //                 map: $this->listen
    //             );
    //         }
    //     );
    // }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }
}

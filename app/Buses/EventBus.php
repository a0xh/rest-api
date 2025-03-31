<?php declare(strict_types=1);

namespace App\Buses;

use App\Contracts\Interface\EventBusInterface;
use Illuminate\Bus\Dispatcher;

final class EventBus implements EventBusInterface
{
    private Dispatcher $eventBus;

    public function __construct(Dispatcher $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function dispatch(object $event): mixed
    {
        return $this->eventBus->dispatch(event: $event);
    }

    public function register(array $map): void
    {
        $this->eventBus->map(map: $map);
    }
}

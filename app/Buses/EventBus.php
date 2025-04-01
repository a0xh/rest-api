<?php declare(strict_types=1);

namespace App\Buses;

use Illuminate\Bus\Dispatcher;
use App\Contracts\Interface\Buses\EventBusInterface;
use App\Shared\Event;

final class EventBus implements EventBusInterface
{
    private Dispatcher $eventBus;

    public function __construct(Dispatcher $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function dispatch(Event $event): mixed
    {
        return $this->eventBus->dispatch(event: $event);
    }

    public function register(array $map): void
    {
        $this->eventBus->map(map: $map);
    }
}

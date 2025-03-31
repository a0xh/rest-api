<?php declare(strict_types=1);

namespace App\Buses;

use App\Contracts\Interface\CommandBusInterface;
use Illuminate\Bus\Dispatcher;

final class CommandBus implements CommandBusInterface
{
    private Dispatcher $commandBus;

    public function __construct(Dispatcher $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function send(object $command): mixed
    {
        return $this->commandBus->dispatch(command: $command);
    }
    
    public function register(array $map): void
    {
        $this->commandBus->map(map: $map);
    }
}

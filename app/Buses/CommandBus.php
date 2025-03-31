<?php declare(strict_types=1);

namespace App\Buses;

use Illuminate\Bus\Dispatcher;
use App\Contracts\Interface\Bus\CommandBusInterface;
use App\Shared\Command;

final class CommandBus implements CommandBusInterface
{
    private Dispatcher $commandBus;

    public function __construct(Dispatcher $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function send(Command $command): mixed
    {
        return $this->commandBus->dispatch(command: $command);
    }
    
    public function register(array $map): void
    {
        $this->commandBus->map(map: $map);
    }
}

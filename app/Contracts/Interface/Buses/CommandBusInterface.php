<?php declare(strict_types=1);

namespace App\Contracts\Interface\Buses;

use App\Shared\Command;
use Illuminate\Bus\Dispatcher;

interface CommandBusInterface
{
    /**
     * The underlying dispatcher for handling command execution.
     *
     * @var \Illuminate\Bus\Dispatcher
     */
    private Dispatcher $commandBus { get; }

    /**
     * Dispatches a command and returns the result.
     *
     * @param \App\Shared\Command $command
     * @return mixed
     */
    public function send(Command $command): mixed;

    /**
     * Registers a mapping of commands to their handlers.
     *
     * @param array $map
     */
    public function register(array $map): void;
}

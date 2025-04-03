<?php declare(strict_types=1);

namespace App\Contracts\Interface\Buses;

use App\Shared\Command;

interface CommandBusInterface
{
    public function send(Command $command): mixed;
    public function register(array $map): void;
}

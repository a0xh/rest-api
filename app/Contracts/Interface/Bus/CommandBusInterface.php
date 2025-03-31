<?php declare(strict_types=1);

namespace App\Contracts\Interface\Bus;

interface CommandBusInterface
{
    public function send(object $command): mixed;
    public function register(array $map): void;
}

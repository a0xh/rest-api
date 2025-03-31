<?php declare(strict_types=1);

namespace App\Contracts\Interface\Bus;

interface QueryBusInterface
{
    public function ask(object $query): mixed;
    public function register(array $map): void;
}

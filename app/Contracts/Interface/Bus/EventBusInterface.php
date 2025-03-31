<?php declare(strict_types=1);

namespace App\Contracts\Interface\Bus;

interface EventBusInterface
{
    public function dispatch(object $event): mixed;
    public function register(array $map): void;
}

<?php declare(strict_types=1);

namespace App\Contracts\Interface\Bus;

use App\Shared\Event;

interface EventBusInterface
{
    public function dispatch(Event $event): mixed;
    public function register(array $map): void;
}

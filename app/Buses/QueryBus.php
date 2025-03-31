<?php declare(strict_types=1);

namespace App\Buses;

use App\Contracts\Interface\QueryBusInterface;
use Illuminate\Bus\Dispatcher;

final class QueryBus implements QueryBusInterface
{
    private Dispatcher $queryBus;

    public function __construct(Dispatcher $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function ask(object $query): mixed
    {
        return $this->queryBus->dispatch(command: $query);
    }
    
    public function register(array $map): void
    {
        $this->queryBus->map(map: $map);
    }
}

<?php declare(strict_types=1);

namespace App\Buses;

use Illuminate\Bus\Dispatcher;
use App\Contracts\Interface\Bus\QueryBusInterface;
use App\Shared\Query;

final class QueryBus implements QueryBusInterface
{
    private Dispatcher $queryBus;

    public function __construct(Dispatcher $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function ask(Query $query): mixed
    {
        return $this->queryBus->dispatch(command: $query);
    }
    
    public function register(array $map): void
    {
        $this->queryBus->map(map: $map);
    }
}

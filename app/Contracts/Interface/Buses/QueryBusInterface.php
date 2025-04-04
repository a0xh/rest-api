<?php declare(strict_types=1);

namespace App\Contracts\Interface\Buses;

use App\Shared\Query;
use Illuminate\Bus\Dispatcher;

interface QueryBusInterface
{
    /**
     * The underlying dispatcher for handling query execution.
     *
     * @var \Illuminate\Bus\Dispatcher
     */
    private Dispatcher $queryBus { get; set; }
    
    /**
     * Executes a query and returns the result.
     *
     * @param \App\Shared\Query $query
     * @return mixed
     */
    public function ask(Query $query): mixed;

    /**
     * Registers a mapping of queries to their handlers.
     *
     * @param array $map
     */
    public function register(array $map): void;
}

<?php declare(strict_types=1);

namespace App\Contracts\Interface\Bus;

use App\Shared\Query;

interface QueryBusInterface
{
    public function ask(Query $query): mixed;
    public function register(array $map): void;
}

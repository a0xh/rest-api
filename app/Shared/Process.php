<?php declare(strict_types=1);

namespace App\Shared;

use Illuminate\Pipeline\Pipeline;

abstract class Process
{
    /**
     * @var callable[]
     */
    protected array $tasks = [];

    /**
     * @param object $payload
     * @return mixed
     */
    protected function run(object $payload): mixed
    {
        return app(
            abstract: Pipeline::class
        )->send(
            passable: $payload,
        )->through(
            pipes: $this->tasks,
        )->thenReturn();
    }
}

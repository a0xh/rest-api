<?php declare(strict_types=1);

namespace App\Shared;

use Illuminate\Pipeline\Pipeline;

abstract class Process
{
    /**
     * Array of tasks that will be executed in the pipeline.
     *
     * @var callable[]
     */
    protected array $tasks = [];

    /**
     * Runs the pipeline with the given payload.
     *
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

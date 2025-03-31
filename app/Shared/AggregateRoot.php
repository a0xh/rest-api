<?php declare(strict_types=1);

namespace App\Shared;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\MappedSuperclass]
abstract class AggregateRoot
{
    /**
     * Collection of recorded domain events.
     * 
     * @var object[] 
     */
    private array $events = [];

    /**
     * Returns the unique identifier of the entity.
     */
    abstract protected function getId(): UuidInterface;

    /**
     * Records a domain event for later dispatch.
     * 
     * @param object $event
     */
    public function record(object $event): void
    {
        $this->events[] = $event;
    }

    /**
     * Releases and clears all recorded domain events.
     * 
     * @return object[]
     */
    public function release(): array
    {
        $events = $this->events;
        $this->events = [];
        
        return $events;
    }
}
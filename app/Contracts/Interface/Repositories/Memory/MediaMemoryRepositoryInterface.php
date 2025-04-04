<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories\Memory;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\MediaRepositoryInterface;
use App\Entities\Media;

interface MediaMemoryRepositoryInterface extends MediaRepositoryInterface
{
    /**
     * Collection of media objects.
     *
     * @var \App\Entities\Media[]
     */
    private array $collection { get; set; }

    /**
     * Retrieves all media.
     *
     * @return \App\Entities\Media[]
     */
    public function all(): array;

    /**
     * Retrieves media by their ID.
     *
     * @param \Ramsey\Uuid\UuidInterface $id
     * @return \App\Entities\Media|null
     */
    public function findById(UuidInterface $id): ?Media;

    /**
     * Retrieves media associated with a specific entity ID.
     *
     * @param string $entityId
     * @return \App\Entities\Media[]
     */
    public function findByEntityId(string $entityId): array;
}

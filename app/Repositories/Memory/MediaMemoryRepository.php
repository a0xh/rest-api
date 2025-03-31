<?php declare(strict_types=1);

namespace App\Repositories\Memory;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repository\Memory\MediaMemoryRepositoryInterface;
use App\Entities\Media;

final class MediaMemoryRepository implements MediaMemoryRepositoryInterface
{
    private array $collection;

    public function __construct(array $media = [])
    {
        $this->collection = $media;
    }

    public function all(): array
    {
        return array_values(array: $this->collection);
    }

    public function findById(UuidInterface $id): ?Media
    {
        return $this->collection[$id->toString()] ?? null;
    }

    public function findByEntityId(string $entityId): array
    {
        $matching = array_filter(array: $this->collection,
            callback: function (Media $media) use ($entityId): bool {
                return $media->getEntityId() === $entityId;
            }
        );

        return array_values(array: $matching);
    }

    public function save(Media $media): void
    {
        $this->collection[] = $media;
    }

    public function remove(Media $media): void
    {
        unset($this->collection[$media->getId()->toString()]);
    }
}

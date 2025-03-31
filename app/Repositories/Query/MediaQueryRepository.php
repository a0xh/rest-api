<?php declare(strict_types=1);

namespace App\Repositories\Query;

use Ramsey\Uuid\UuidInterface;
use App\Repositories\Cached\MediaCachedRepository;
use App\Contracts\Abstract\MediaRepositoryAbstract;
use App\Repositories\Memory\MediaMemoryRepository;
use App\Entities\Media;

final class MediaQueryRepository extends MediaRepositoryAbstract
{
    public function __construct(
        private MediaCachedRepository $cachedRepository,
        private MediaMemoryRepository $memoryRepository
    ) {
        parent::__construct(
            repository: $cachedRepository,
            collection: $memoryRepository
        );
    }

    public function all(): array
    {
        $memory = $this->collection->all();

        if (!empty($memory)) {
            return $memory;
        }

        $cached = $this->repository->all();

        return $cached;
    }

    public function findById(UuidInterface $id): ?Media
    {
        $memory = $this->collection->findById(id: $id);

        if ($memory !== null) {
            return $memory;
        }

        $cached = $this->repository->findById(id: $id);

        return $cached;
    }

    public function findByEntityId(string $entityId): array
    {
        $memory = $this->collection->findByEntityId(
            entityId: $entityId
        );

        if ($memory !== null) {
            return $memory;
        }
        
        $cached = $this->repository->findByEntityId(
            entityId: $entityId
        );
        
        return $cached;
    }

    public function save(Media $media): void
    {
        $this->collection->save(media: $media);
        $this->repository->save(media: $media);
    }

    public function remove(Media $media): void
    {
        $this->collection->remove(media: $media);
        $this->repository->remove(media: $media);
    }
}

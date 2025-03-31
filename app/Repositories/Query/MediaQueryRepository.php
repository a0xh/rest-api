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
            storageRepository: $cachedRepository,
            memoryRepository: $memoryRepository
        );
    }

    public function all(): array
    {
        $memory = $this->memoryRepository->all();

        if (!empty($memory)) {
            return $memory;
        }

        $cached = $this->storageRepository->all();

        return $cached;
    }

    public function findById(UuidInterface $id): ?Media
    {
        $memory = $this->memoryRepository->findById(id: $id);

        if ($memory !== null) {
            return $memory;
        }

        $cached = $this->storageRepository->findById(id: $id);

        return $cached;
    }

    public function findByEntityId(string $entityId): array
    {
        $memory = $this->memoryRepository->findByEntityId(
            entityId: $entityId
        );

        if ($memory !== null) {
            return $memory;
        }
        
        $cached = $this->storageRepository->findByEntityId(
            entityId: $entityId
        );
        
        return $cached;
    }

    public function save(Media $media): void
    {
        $this->memoryRepository->save(media: $media);
        $this->storageRepository->save(media: $media);
    }

    public function remove(Media $media): void
    {
        $this->memoryRepository->remove(media: $media);
        $this->storageRepository->remove(media: $media);
    }
}

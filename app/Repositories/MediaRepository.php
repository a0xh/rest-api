<?php declare(strict_types=1);

namespace App\Repositories;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\Storage\MediaStorageRepositoryInterface;
use App\Contracts\Interface\Repositories\Memory\MediaMemoryRepositoryInterface;
use App\Contracts\Abstract\MediaRepositoryAbstract;
use App\Entities\Media;

final class MediaRepository extends MediaRepositoryAbstract
{
    public function __construct(
        protected MediaStorageRepositoryInterface $storageRepository,
        protected MediaMemoryRepositoryInterface $memoryRepository
    ) {
        parent::__construct(
            storageRepository: $storageRepository,
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

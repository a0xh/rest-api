<?php declare(strict_types=1);

namespace App\Contracts\Abstract;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\MediaRepositoryInterface;
use App\Contracts\Interface\Repositories\Storage\MediaStorageRepositoryInterface;
use App\Contracts\Interface\Repositories\Memory\MediaMemoryRepositoryInterface;
use App\Entities\Media;

abstract class MediaRepositoryAbstract implements MediaRepositoryInterface
{
    protected function __construct(
        protected MediaStorageRepositoryInterface $storageRepository,
        protected MediaMemoryRepositoryInterface $memoryRepository
    ) {
        if (count(value: $this->memoryRepository->all()) === 0)
        {
            $collection = collect(value: $this->storageRepository->all());

            $collection->each(callback: function(Media $media): void {
                $this->memoryRepository->save(media: $media);
            });
        }
    }

    abstract protected function all(): array;
    abstract protected function findById(UuidInterface $id): ?Media;
    abstract protected function findByEntityId(string $entityId): array;
}

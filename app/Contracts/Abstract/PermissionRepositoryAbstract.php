<?php declare(strict_types=1);

namespace App\Contracts\Abstract;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repository\Storage\PermissionStorageRepositoryInterface;
use App\Contracts\Interface\Repository\Memory\PermissionMemoryRepositoryInterface;
use App\Entities\Permission;

abstract class PermissionRepositoryAbstract implements PermissionStorageRepositoryInterface
{
    protected function __construct(
        private readonly PermissionStorageRepositoryInterface $storageRepository,
        private PermissionMemoryRepositoryInterface $memoryRepository
    ) {
        if (count(value: $this->memoryRepository->all()) === 0)
        {
            $collection = collect(value: $this->storageRepository->all());

            $collection->each(callback: function(Permission $permission): void {
                $this->memoryRepository->save(permission: $permission);
            });
        }
    }

    abstract protected function all(): array;
    abstract protected function findById(UuidInterface $id): ?Permission;
    abstract protected function findBySlug(string $slug): ?Permission;
}

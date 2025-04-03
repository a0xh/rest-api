<?php declare(strict_types=1);

namespace App\Repositories;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\Storage\PermissionStorageRepositoryInterface;
use App\Contracts\Interface\Repositories\Memory\PermissionMemoryRepositoryInterface;
use App\Contracts\Abstract\PermissionRepositoryAbstract;
use App\Entities\Permission;

final class PermissionRepository extends PermissionRepositoryAbstract
{
    public function __construct(
        protected PermissionStorageRepositoryInterface $storageRepository,
        protected PermissionMemoryRepositoryInterface $memoryRepository
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

        $cached = $this->cachedRepository->all();

        return $cached;
    }

    public function findById(UuidInterface $id): ?Permission
    {
        $memory = $this->memoryRepository->findById(id: $id);

        if ($memory !== null) {
            return $memory;
        }

        $cached = $this->cachedRepository->findById(id: $id);

        return $cached;
    }

    public function findBySlug(string $slug): ?Permission
    {
        $memory = $this->memoryRepository->findBySlug(slug: $slug);

        if ($memory !== null) {
            return $memory;
        }
        
        $cached = $this->cachedRepository->findBySlug(slug: $slug);
        
        return $cached;
    }

    public function save(Permission $permission): void
    {
        $this->memoryRepository->save(permission: $permission);
        $this->cachedRepository->save(permission: $permission);
    }

    public function remove(Permission $permission): void
    {
        $this->memoryRepository->remove(permission: $permission);
        $this->cachedRepository->remove(permission: $permission);
    }
}

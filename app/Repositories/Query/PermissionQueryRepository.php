<?php declare(strict_types=1);

namespace App\Repositories\Query;

use Ramsey\Uuid\UuidInterface;
use App\Repositories\Cached\PermissionCachedRepository;
use App\Contracts\Abstract\PermissionRepositoryAbstract;
use App\Repositories\Memory\PermissionMemoryRepository;
use App\Entities\Permission;

final class PermissionQueryRepository extends PermissionRepositoryAbstract
{
    public function __construct(
        private PermissionCachedRepository $cachedRepository,
        private PermissionMemoryRepository $memoryRepository
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

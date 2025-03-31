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

    public function findById(UuidInterface $id): ?Permission
    {
        $memory = $this->collection->findById(id: $id);

        if ($memory !== null) {
            return $memory;
        }

        $cached = $this->repository->findById(id: $id);

        return $cached;
    }

    public function findBySlug(string $slug): ?Permission
    {
        $memory = $this->collection->findBySlug(slug: $slug);

        if ($memory !== null) {
            return $memory;
        }
        
        $cached = $this->repository->findBySlug(slug: $slug);
        
        return $cached;
    }

    public function save(Permission $permission): void
    {
        $this->collection->save(permission: $permission);
        $this->repository->save(permission: $permission);
    }

    public function remove(Permission $permission): void
    {
        $this->collection->remove(permission: $permission);
        $this->repository->remove(permission: $permission);
    }
}

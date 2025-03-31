<?php declare(strict_types=1);

namespace App\Repositories\Query;

use Ramsey\Uuid\UuidInterface;
use App\Repositories\Cached\RoleCachedRepository;
use App\Contracts\Abstract\RoleRepositoryAbstract;
use App\Repositories\Memory\RoleMemoryRepository;
use App\Entities\Role;

final class RoleQueryRepository extends RoleRepositoryAbstract
{
    public function __construct(
        private RoleCachedRepository $cachedRepository,
        private RoleMemoryRepository $memoryRepository
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

    public function findById(UuidInterface $id): ?Role
    {
        $memory = $this->collection->findById(id: $id);

        if ($memory !== null) {
            return $memory;
        }

        $cached = $this->repository->findById(id: $id);

        return $cached;
    }

    public function findBySlug(string $slug): ?Role
    {
        $memory = $this->collection->findBySlug(slug: $slug);

        if ($memory !== null) {
            return $memory;
        }
        
        $cached = $this->repository->findBySlug(slug: $slug);
        
        return $cached;
    }

    public function save(Role $role): void
    {
        $this->collection->save(role: $role);
        $this->repository->save(role: $role);
    }

    public function remove(Role $role): void
    {
        $this->collection->remove(role: $role);
        $this->repository->remove(role: $role);
    }
}

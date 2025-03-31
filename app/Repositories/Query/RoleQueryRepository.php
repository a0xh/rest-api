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

    public function findById(UuidInterface $id): ?Role
    {
        $memory = $this->memoryRepository->findById(id: $id);

        if ($memory !== null) {
            return $memory;
        }

        $cached = $this->storageRepository->findById(id: $id);

        return $cached;
    }

    public function findBySlug(string $slug): ?Role
    {
        $memory = $this->memoryRepository->findBySlug(slug: $slug);

        if ($memory !== null) {
            return $memory;
        }
        
        $cached = $this->memoryRepository->findBySlug(slug: $slug);
        
        return $cached;
    }

    public function save(Role $role): void
    {
        $this->memoryRepository->save(role: $role);
        $this->storageRepository->save(role: $role);
    }

    public function remove(Role $role): void
    {
        $this->memoryRepository->remove(role: $role);
        $this->storageRepository->remove(role: $role);
    }
}

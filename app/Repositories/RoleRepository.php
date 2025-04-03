<?php declare(strict_types=1);

namespace App\Repositories;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\Storage\RoleStorageRepositoryInterface;
use App\Contracts\Interface\Repositories\Memory\RoleMemoryRepositoryInterface;
use App\Contracts\Abstract\RoleRepositoryAbstract;
use App\Entities\Role;

final class RoleRepository extends RoleRepositoryAbstract
{
    public function __construct(
        protected RoleStorageRepositoryInterface $storageRepository,
        protected RoleMemoryRepositoryInterface $memoryRepository
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

<?php declare(strict_types=1);

namespace App\Contracts\Abstract;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\RoleRepositoryInterface;
use App\Contracts\Interface\Repositories\Storage\RoleStorageRepositoryInterface;
use App\Contracts\Interface\Repositories\Memory\RoleMemoryRepositoryInterface;
use App\Entities\Role;

abstract class RoleRepositoryAbstract implements RoleRepositoryInterface
{
    protected function __construct(
        protected RoleStorageRepositoryInterface $storageRepository,
        protected RoleMemoryRepositoryInterface $memoryRepository
    ) {
        if (count(value: $this->memoryRepository->all()) === 0)
        {
            $collection = collect(value: $this->storageRepository->all());

            $collection->each(callback: function(Role $role): void {
                $this->memoryRepository->save(role: $role);
            });
        }
    }

    abstract protected function all(): array;
    abstract protected function findById(UuidInterface $id): ?Role;
    abstract protected function findBySlug(string $slug): ?Role;
}

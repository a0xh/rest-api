<?php declare(strict_types=1);

namespace App\Repositories\Memory;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\Memory\RoleMemoryRepositoryInterface;
use App\Entities\Role;

final class RoleMemoryRepository implements RoleMemoryRepositoryInterface
{
    private array $collection;

    public function __construct(array $roles = [])
    {
        $this->collection = $roles;
    }

    public function all(): array
    {
        return array_values(array: $this->collection);
    }

    public function findById(UuidInterface $id): ?Role
    {
        return $this->collection[$id->toString()] ?? null;
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->collection[$slug] ?? null;
    }

    public function save(Role $role): void
    {
        $this->collection[] = $role;
    }

    public function remove(Role $role): void
    {
        unset($this->collection[$role->getId()->toString()]);
    }
}

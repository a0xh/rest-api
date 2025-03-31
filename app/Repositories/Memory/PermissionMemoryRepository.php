<?php declare(strict_types=1);

namespace App\Repositories\Memory;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Abstract\PermissionRepositoryAbstract;
use App\Entities\Permission;

final class PermissionMemoryRepository extends PermissionRepositoryAbstract
{
    private array $collection;

    public function __construct(array $permissions = [])
    {
        $this->collection = $permissions;
    }

    public function all(): array
    {
        return array_values(array: $this->collection);
    }

    public function findById(UuidInterface $id): ?Permission
    {
        return $this->collection[$id->toString()] ?? null;
    }

    public function findBySlug(string $slug): ?Permission
    {
        return $this->collection[$slug] ?? null;
    }

    public function save(Permission $permission): void
    {
        $this->collection[] = $permission;
    }

    public function remove(Permission $permission): void
    {
        unset($this->collection[$permission->getId()->toString()]);
    }
}

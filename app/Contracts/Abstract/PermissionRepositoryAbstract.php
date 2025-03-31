<?php declare(strict_types=1);

namespace App\Contracts\Abstract;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repository\PermissionRepositoryInterface;
use App\Entities\Permission;

abstract class PermissionRepositoryAbstract implements PermissionRepositoryInterface
{
    private PermissionRepositoryInterface $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    abstract protected function all(): array;
    abstract protected function findById(UuidInterface $id): ?Permission;
    abstract protected function findBySlug(string $slug): ?Permission;
}

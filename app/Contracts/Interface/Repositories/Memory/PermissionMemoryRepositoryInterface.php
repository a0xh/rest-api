<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories\Memory;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\PermissionRepositoryInterface;
use App\Entities\Permission;

interface PermissionMemoryRepositoryInterface extends PermissionRepositoryInterface
{
    public function all(): array;
    public function findById(UuidInterface $id): ?Permission;
    public function findBySlug(string $slug): ?Permission;
}

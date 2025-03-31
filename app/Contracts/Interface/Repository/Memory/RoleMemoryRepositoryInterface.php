<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repository\Memory;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repository\Storage\RoleStorageRepositoryInterface;
use App\Entities\Role;

interface RoleMemoryRepositoryInterface extends RoleStorageRepositoryInterface
{
    public function all(): array;
    public function findById(UuidInterface $id): ?Role;
    public function findBySlug(string $slug): ?Role;
}

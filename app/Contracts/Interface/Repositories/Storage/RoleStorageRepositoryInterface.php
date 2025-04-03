<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories\Storage;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\RoleRepositoryInterface;
use App\Entities\Role;

interface RoleStorageRepositoryInterface extends RoleRepositoryInterface
{
    public function all(): array;
    public function findById(UuidInterface $id): ?Role;
    public function findBySlug(string $slug): ?Role;
}

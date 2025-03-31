<?php declare(strict_types=1);

namespace App\Contracts\Abstract;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\RoleRepositoryInterface;
use App\Entities\Role;

abstract class RoleRepositoryAbstract implements RoleRepositoryInterface
{
    private RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    abstract protected function all(): array;
    abstract protected function findById(UuidInterface $id): ?Role;
    abstract protected function findBySlug(string $slug): ?Role;
}

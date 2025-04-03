<?php declare(strict_types=1);

namespace App\Repositories\Storage\Queries;

use App\Entities\Role;
use App\Contracts\Interface\Repositories\Storage\RoleStorageRepositoryInterface;
use Doctrine\ORM\{EntityManagerInterface, ORMException};
use Ramsey\Uuid\UuidInterface;

final class RoleQueryRepository implements RoleStorageRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function all(): array
    {
        return $this->entityManager->getRepository(
            className: Role::class
        )->findBy(
            criteria: [],
            orderBy: ['createdAt' => 'DESC']
        );
    }

    public function findById(UuidInterface $id): ?Role
    {
        return $this->entityManager->getRepository(
            className: Role::class
        )->find(
            id: $id
        );
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->entityManager->getRepository(
            className: Role::class
        )->findOneBy(
            criteria: ['slug' => $slug]
        );
    }

    public function save(Role $role): void
    {
        try {
            $this->entityManager->persist(object: $role);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed to save role: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }

    public function remove(Role $role): void
    {
        try {
            $this->entityManager->remove(object: $role);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed to delete role: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}

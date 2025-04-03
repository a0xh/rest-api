<?php declare(strict_types=1);

namespace App\Repositories\Storage\Queries;

use App\Entities\Permission;
use App\Contracts\Interface\Repositories\Storage\PermissionStorageRepositoryInterface;
use Doctrine\ORM\{EntityManagerInterface, ORMException};
use Ramsey\Uuid\UuidInterface;

final class PermissionQueryRepository implements PermissionStorageRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function all(): array
    {
        return $this->entityManager->getRepository(
            className: Permission::class
        )->findBy(
            criteria: [],
            orderBy: ['createdAt' => 'DESC']
        );
    }

    public function findById(UuidInterface $id): ?Permission
    {
        return $this->entityManager->getRepository(
            className: Permission::class
        )->find(
            id: $id
        );
    }

    public function findBySlug(string $slug): ?Permission
    {
        return $this->entityManager->getRepository(
            className: Permission::class
        )->findOneBy(
            criteria: ['slug' => $slug]
        );
    }

    public function save(Permission $permission): void
    {
        try {
            $this->entityManager->persist(object: $permission);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed to save permission: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }

    public function remove(Permission $permission): void
    {
        try {
            $this->entityManager->remove(object: $permission);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed to delete permission: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}

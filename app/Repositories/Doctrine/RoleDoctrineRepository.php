<?php declare(strict_types=1);

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use App\Contracts\Abstract\RoleRepositoryAbstract;
use Ramsey\Uuid\UuidInterface;
use App\Entities\Role;

final class RoleDoctrineRepository extends RoleRepositoryAbstract
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
                message: "Failed To Save Role: {$e->getMessage()}",
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
                message: "Failed To Delete Role: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}

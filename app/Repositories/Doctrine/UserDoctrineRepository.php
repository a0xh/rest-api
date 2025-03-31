<?php declare(strict_types=1);

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\Common\Collections\Criteria;
use Ramsey\Uuid\UuidInterface;
use App\Contracts\Abstract\UserRepositoryAbstract;
use App\Entities\User;

final class UserDoctrineRepository extends UserRepositoryAbstract
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function all(): array
    {
        return $this->entityManager->getRepository(
            className: User::class
        )->findBy(
            criteria: [],
            orderBy: ['createdAt' => Criteria::DESC]
        );
    }

    public function findById(UuidInterface $id): ?User
    {
        return $this->entityManager->getRepository(
            className: User::class
        )->find(
            id: $id
        );
    }

    public function findByEmail(string $email): ?User
    {
        return $this->entityManager->getRepository(
            className: User::class
        )->findOneBy(
            criteria: ['email' => $email]
        );
    }

    public function save(User $user): void
    {
        try {
            $this->entityManager->persist(object: $user);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed To Save User: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }

    public function remove(User $user): void
    {
        try {
            $this->entityManager->remove(object: $user);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed To Delete User: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}

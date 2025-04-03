<?php declare(strict_types=1);

namespace App\Repositories\Storage\Queries;

use App\Entities\User;
use App\Contracts\Interface\Repositories\Storage\UserStorageRepositoryInterface;
use Doctrine\ORM\{EntityManagerInterface, ORMException};
use Ramsey\Uuid\UuidInterface;

final class UserQueryRepository implements UserStorageRepositoryInterface
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
            orderBy: ['createdAt' => 'DESC']
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
            Log::error(
                message: 'User creation failed: ' . $e->getMessage(),
                context: [
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );

            throw new \RuntimeException(
                message: "Failed to save user: {$e->getMessage()}",
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
            Log::error(
                message: 'User deletion failed: ' . $e->getMessage(),
                context: [
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );

            throw new \RuntimeException(
                message: "Failed to delete user: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}

<?php declare(strict_types=1);

namespace App\Repositories;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Abstract\UserRepositoryAbstract;
use App\Contracts\Interface\Repositories\Storage\UserStorageRepositoryInterface;
use App\Contracts\Interface\Repositories\Memory\UserMemoryRepositoryInterface;
use App\Entities\User;

final class UserRepository extends UserRepositoryAbstract
{
    public function __construct(
        protected UserStorageRepositoryInterface $storageRepository,
        protected UserMemoryRepositoryInterface $memoryRepository
    ) {
        parent::__construct(
            storageRepository: $storageRepository,
            memoryRepository: $memoryRepository
        );

    }

    public function all(): array
    {
        $memory = $this->memoryRepository->all();

        if (!empty($memory)) {
            return $memory;
        }

        $cached = $this->storageRepository->all();

        return $cached;
    }

    public function findById(UuidInterface $id): ?User
    {
        $memory = $this->memoryRepository->findById(id: $id);

        if ($memory !== null) {
            return $memory;
        }

        $cached = $this->storageRepository->findById(id: $id);

        return $cached;
    }

    public function findByEmail(string $email): ?User
    {
        $memory = $this->memoryRepository->findByEmail(email: $email);

        if ($memory !== null) {
            return $memory;
        }
        
        $cached = $this->storageRepository->findByEmail(email: $email);
        
        return $cached;
    }

    public function save(User $user): void
    {
        $this->memoryRepository->save(user: $user);
        $this->storageRepository->save(user: $user);
    }

    public function remove(User $user): void
    {
        $this->memoryRepository->remove(user: $user);
        $this->storageRepository->remove(user: $user);
    }
}

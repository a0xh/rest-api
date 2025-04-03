<?php declare(strict_types=1);

namespace App\Contracts\Abstract;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\UserRepositoryInterface;
use App\Contracts\Interface\Repositories\Storage\UserStorageRepositoryInterface;
use App\Contracts\Interface\Repositories\Memory\UserMemoryRepositoryInterface;
use App\Entities\User;

abstract class UserRepositoryAbstract implements UserRepositoryInterface
{
    protected function __construct(
        protected UserStorageRepositoryInterface $storageRepository,
        protected UserMemoryRepositoryInterface $memoryRepository
    ) {
        if (count(value: $this->memoryRepository->all()) === 0)
        {
            $collection = collect(value: $this->storageRepository->all());

            $collection->each(callback: function(User $user): void {
                $this->memoryRepository->save(user: $user);
            });
        }
    }

    abstract protected function all(): array;
    abstract protected function findById(UuidInterface $id): ?User;
    abstract protected function findByEmail(string $email): ?User;
}

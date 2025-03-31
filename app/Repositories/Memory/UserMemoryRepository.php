<?php declare(strict_types=1);

namespace App\Repositories\Memory;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Abstract\UserRepositoryAbstract;
use App\Entities\User;

final class UserMemoryRepository extends UserRepositoryAbstract
{
    private array $collection;

    public function __construct(array $users = [])
    {
        $this->collection = $users;
    }

    public function all(): array
    {
        return array_values(array: $this->collection);
    }

    public function findById(UuidInterface $id): ?User
    {
        return $this->collection[$id->toString()] ?? null;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->collection[$email] ?? null;
    }

    public function save(User $user): void
    {
        $this->collection[] = $user;
    }

    public function remove(User $user): void
    {
        unset($this->collection[$user->getId()->toString()]);
    }
}

<?php declare(strict_types=1);

namespace App\Repositories\Query;

use Ramsey\Uuid\UuidInterface;
use App\Repositories\Cached\UserCachedRepository;
use App\Contracts\Abstract\UserRepositoryAbstract;
use App\Repositories\Memory\UserMemoryRepository;
use App\Entities\User;

final class UserQueryRepository extends UserRepositoryAbstract
{
    public function __construct(
        private UserCachedRepository $cached,
        private UserMemoryRepository $memory
    ) {
        parent::__construct(repository: $cached, collection: $memory);
    }

    public function all(): array
    {
        $memory = $this->collection->all();

        if (!empty($memory)) {
            return $memory;
        }

        $cached = $this->repository->all();

        return $cached;
    }

    public function findById(UuidInterface $id): ?User
    {
        $memory = $this->collection->findById(id: $id);

        if ($memory !== null) {
            return $memory;
        }

        $cached = $this->repository->findById(id: $id);

        return $cached;
    }

    public function findByEmail(string $email): ?User
    {
        $memory = $this->collection->findByEmail(email: $email);

        if ($memory !== null) {
            return $memory;
        }
        
        $cached = $this->repository->findByEmail(email: $email);
        
        return $cached;
    }

    public function save(User $user): void
    {
        $this->collection->save(user: $user);
        $this->repository->save(user: $user);
    }

    public function remove(User $user): void
    {
        $this->collection->remove(user: $user);
        $this->repository->remove(user: $user);
    }
}

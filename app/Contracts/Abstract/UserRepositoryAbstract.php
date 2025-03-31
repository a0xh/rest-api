<?php declare(strict_types=1);

namespace App\Contracts\Abstract;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\UserRepositoryInterface;
use App\Entities\User;

abstract class UserRepositoryAbstract implements UserRepositoryInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    abstract protected function all(): array;
    abstract protected function findById(UuidInterface $id): ?User;
    abstract protected function findByEmail(string $email): ?User;
}

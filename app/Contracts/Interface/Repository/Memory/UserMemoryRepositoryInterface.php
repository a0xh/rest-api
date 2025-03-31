<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repository\Memory;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repository\Storage\UserStorageRepositoryInterface;
use App\Entities\User;

interface UserMemoryRepositoryInterface extends UserStorageRepositoryInterface
{
    public function all(): array;
    public function findById(UuidInterface $id): ?User;
    public function findByEmail(string $email): ?User;
}

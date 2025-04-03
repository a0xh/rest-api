<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories\Storage;

use App\Contracts\Interface\Repositories\UserRepositoryInterface;

use Ramsey\Uuid\UuidInterface;
use App\Entities\User;

interface UserStorageRepositoryInterface extends UserRepositoryInterface
{
    public function all(): array;
    public function findById(UuidInterface $id): ?User;
    public function findByEmail(string $email): ?User;
}

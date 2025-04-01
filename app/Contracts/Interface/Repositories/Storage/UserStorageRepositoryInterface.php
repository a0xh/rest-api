<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories\Storage;

use App\Contracts\Interface\Repositories\UserRepositoryInterface;
use App\Entities\User;

interface UserStorageRepositoryInterface extends UserRepositoryInterface
{
    public function save(User $user): void;
    public function remove(User $user): void;
}

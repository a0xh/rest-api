<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repository\Storage;

use App\Entities\User;

interface UserStorageRepositoryInterface
{
    public function save(User $user): void;
    public function remove(User $user): void;
}

<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories;

use App\Entities\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function remove(User $user): void;
}

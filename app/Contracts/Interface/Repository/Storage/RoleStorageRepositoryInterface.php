<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repository\Storage;

use App\Entities\Role;

interface RoleStorageRepositoryInterface
{
    public function save(Role $role): void;
    public function remove(Role $role): void;
}

<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories;

use App\Entities\Role;

interface RoleRepositoryInterface
{
    public function save(Role $role): void;
    public function remove(Role $role): void;
}

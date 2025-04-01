<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories\Storage;

use App\Contracts\Interface\Repositories\RoleRepositoryInterface;
use App\Entities\Role;

interface RoleStorageRepositoryInterface extends RoleRepositoryInterface
{
    public function save(Role $role): void;
    public function remove(Role $role): void;
}

<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repository;

use App\Entities\Permission;

interface PermissionRepositoryInterface
{
    public function save(Permission $permission): void;
    public function remove(Permission $permission): void;
}

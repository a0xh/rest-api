<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repository\Storage;

use App\Entities\Permission;

interface PermissionStorageRepositoryInterface
{
    public function save(Permission $permission): void;
    public function remove(Permission $permission): void;
}

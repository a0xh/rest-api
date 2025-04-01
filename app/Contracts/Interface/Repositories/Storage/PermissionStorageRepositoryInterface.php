<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories\Storage;

use App\Contracts\Interface\Repositories\PermissionRepositoryInterface;
use App\Entities\Permission;

interface PermissionStorageRepositoryInterface extends PermissionRepositoryInterface
{
    public function save(Permission $permission): void;
    public function remove(Permission $permission): void;
}

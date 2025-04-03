<?php declare(strict_types=1);

namespace App\Repositories\Storage\Cached;

use App\Entities\Permission;
use App\Repositories\Storage\Transactions\PermissionTransactionRepository;
use Illuminate\Support\Facades\Cache;
use App\Contracts\Interface\Repositories\Storage\PermissionStorageRepositoryInterface;
use Ramsey\Uuid\UuidInterface;
use App\Repositories\Storage\Queries\PermissionQueryRepository;
use Carbon\Carbon;

final class PermissionCachedRepository implements PermissionStorageRepositoryInterface
{
	private const CACHE_PERMISSION_ALL_KEY = 'permissions';

	public function __construct(
		private PermissionQueryRepository $permissionQuery,
        private PermissionTransactionRepository $permissionTransaction
	) {}

	public function all(): array
    {
        return Cache::flexible(
        	key: self::CACHE_PERMISSION_ALL_KEY,
        	ttl: [
                Carbon::now()->addMinutes(value: 5),
                Carbon::now()->addMinutes(value: 15)
            ],
        	callback: fn () => $this->permissionQuery->all(),
        	lock: ['seconds' => 10]
        );
    }

    public function findById(UuidInterface $id): ?Permission
    {
        return $this->permissionQuery->findById(id: $id);
    }

    public function findBySlug(string $slug): ?Permission
    {
        return $this->permissionQuery->findBySlug(slug: $slug);
    }

    public function save(Permission $permission): void
    {
        $this->permissionTransaction->save(permission: $permission);

    	if (Cache::has(key: self::CACHE_PERMISSION_ALL_KEY)) {
    		Cache::forget(key: self::CACHE_PERMISSION_ALL_KEY);
    	}
    }

    public function remove(Permission $permission): void
    {
    	$this->permissionTransaction->remove(permission: $permission);

    	if (Cache::has(key: self::CACHE_PERMISSION_ALL_KEY)) {
    		Cache::forget(key: self::CACHE_PERMISSION_ALL_KEY);
    	}
    }
}

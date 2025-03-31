<?php declare(strict_types=1);

namespace App\Repositories\Cached;

use App\Contracts\Abstract\PermissionRepositoryAbstract;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Transaction\PermissionTransactionRepository;
use Ramsey\Uuid\UuidInterface;
use App\Repositories\Doctrine\PermissionDoctrineRepository;
use App\Entities\Permission;
use Carbon\Carbon;

final class PermissionCachedRepository extends PermissionRepositoryAbstract
{
	private const CACHE_PERMISSION_ALL_KEY = 'permissions';

	public function __construct(
		private PermissionDoctrineRepository $permissionDoctrine,
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
        	callback: fn () => $this->permissionDoctrine->all(),
        	lock: ['seconds' => 10]
        );
    }

    public function findById(UuidInterface $id): ?Permission
    {
        return $this->permissionDoctrine->findById(id: $id);
    }

    public function findBySlug(string $slug): ?Permission
    {
        return $this->permissionDoctrine->findBySlug(slug: $slug);
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

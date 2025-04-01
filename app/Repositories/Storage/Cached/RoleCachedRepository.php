<?php declare(strict_types=1);

namespace App\Repositories\Storage\Cached;

use App\Contracts\Abstract\RoleRepositoryAbstract;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Storage\Transactions\RoleTransactionRepository;
use Ramsey\Uuid\UuidInterface;
use App\Repositories\Storage\Queries\RoleQueryRepository;
use App\Entities\Role;
use Carbon\Carbon;

final class RoleCachedRepository extends RoleRepositoryAbstract
{
	private const CACHE_ROLE_ALL_KEY = 'roles';

	public function __construct(
		private RoleQueryRepository $roleQuery,
        private RoleTransactionRepository $roleTransaction
	) {}

	public function all(): array
    {
        return Cache::flexible(
        	key: self::CACHE_ROLE_ALL_KEY,
        	ttl: [
                Carbon::now()->addMinutes(value: 5),
                Carbon::now()->addMinutes(value: 15)
            ],
        	callback: fn () => $this->roleQuery->all(),
        	lock: ['seconds' => 10]
        );
    }

    public function findById(UuidInterface $id): ?Role
    {
        return $this->roleQuery->findById(id: $id);
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->roleQuery->findBySlug(slug: $slug);
    }

    public function save(Role $role): void
    {
        $this->roleTransaction->save(role: $role);

    	if (Cache::has(key: self::CACHE_ROLE_ALL_KEY)) {
    		Cache::forget(key: self::CACHE_ROLE_ALL_KEY);
    	}
    }

    public function remove(Role $role): void
    {
    	$this->roleTransaction->remove(role: $role);

    	if (Cache::has(key: self::CACHE_ROLE_ALL_KEY)) {
    		Cache::forget(key: self::CACHE_ROLE_ALL_KEY);
    	}
    }
}

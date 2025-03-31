<?php declare(strict_types=1);

namespace App\Repositories\Cached;

use App\Contracts\Abstract\RoleRepositoryAbstract;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Transaction\RoleTransactionRepository;
use Ramsey\Uuid\UuidInterface;
use App\Repositories\Doctrine\RoleDoctrineRepository;
use App\Entities\Role;
use Carbon\Carbon;

final class RoleCachedRepository extends RoleRepositoryAbstract
{
	private const CACHE_ROLE_ALL_KEY = 'roles';

	public function __construct(
		private RoleDoctrineRepository $roleDoctrine,
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
        	callback: fn () => $this->roleDoctrine->all(),
        	lock: ['seconds' => 10]
        );
    }

    public function findById(UuidInterface $id): ?Role
    {
        return $this->roleDoctrine->findById(id: $id);
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->roleDoctrine->findBySlug(slug: $slug);
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

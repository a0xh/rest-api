<?php declare(strict_types=1);

namespace App\Repositories\Storage\Cached;

use App\Entities\User;
use App\Repositories\Storage\Transactions\UserTransactionRepository;
use Illuminate\Support\Facades\Cache;
use App\Contracts\Interface\Repositories\Storage\UserStorageRepositoryInterface;
use Ramsey\Uuid\UuidInterface;
use App\Repositories\Storage\Queries\UserQueryRepository;
use Carbon\Carbon;

final class UserCachedRepository implements UserStorageRepositoryInterface
{
	private const CACHE_USER_ALL_KEY = 'users';

	public function __construct(
		private UserQueryRepository $userQuery,
        private UserTransactionRepository $userTransaction
	) {}

	public function all(): array
    {
        return Cache::flexible(
        	key: self::CACHE_USER_ALL_KEY,
        	ttl: [
                Carbon::now()->addMinutes(value: 5),
                Carbon::now()->addMinutes(value: 15)
            ],
        	callback: fn () => $this->userQuery->all(),
        	lock: ['seconds' => 10]
        );
    }

    public function findById(UuidInterface $id): ?User
    {
        return $this->userQuery->findById(id: $id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userQuery->findByEmail(email: $email);
    }

    public function save(User $user): void
    {
        $this->userTransaction->save(user: $user);

    	if (Cache::has(key: self::CACHE_USER_ALL_KEY)) {
    		Cache::forget(key: self::CACHE_USER_ALL_KEY);
    	}
    }

    public function remove(User $user): void
    {
    	$this->userTransaction->remove(user: $user);

    	if (Cache::has(key: self::CACHE_USER_ALL_KEY)) {
    		Cache::forget(key: self::CACHE_USER_ALL_KEY);
    	}
    }
}

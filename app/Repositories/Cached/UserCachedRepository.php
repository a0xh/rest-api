<?php declare(strict_types=1);

namespace App\Repositories\Cached;

use App\Contracts\Abstract\UserRepositoryAbstract;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Transaction\UserTransactionRepository;
use Ramsey\Uuid\UuidInterface;
use App\Repositories\Doctrine\UserDoctrineRepository;
use App\Entities\User;

final class UserCachedRepository extends UserRepositoryAbstract
{
	private const CACHE_USER_ALL_KEY = 'users';

	public function __construct(
		private UserDoctrineRepository $userDoctrine,
        private UserTransactionRepository $userTransaction,
	) {}

	public function all(): array
    {
        return Cache::flexible(
        	key: self::CACHE_USER_ALL_KEY,
        	ttl: [900, 1800],
        	callback: fn () => $this->userDoctrine->all(),
        	lock: ['seconds' => 10]
        );
    }

    public function findById(UuidInterface $id): ?User
    {
        return $this->userDoctrine->findById(id: $id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->userDoctrine->findByEmail(email: $email);
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

<?php declare(strict_types=1);

namespace App\Repositories\Storage\Transactions;

use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Interface\Repositories\UserRepositoryInterface;
use Illuminate\Database\QueryException;
use App\Repositories\Storage\Queries\UserQueryRepository;
use App\Entities\User;

final class UserTransactionRepository implements UserRepositoryInterface
{
	private UserQueryRepository $userQuery;

	public function __construct(UserQueryRepository $userQuery)
	{
		$this->userQuery = $userQuery;
	}

	public function save(User $user): void
	{
		try {
	        DB::transaction(
	            callback: fn () => $this->userQuery->save(user: $user),
	            attempts: 3
	        );
	    }

	    catch (QueryException $e) {
	        Log::error(
	            message: 'Database Error: ' . $e->getMessage(),
	            context: [
	                'code' => $e->getCode(),
	                'bindings' => $e->getBindings(),
	                'sql' => $e->getSql()
	            ]
	        );

	        throw new \RuntimeException(
	            message: 'Error Saving User: ' . $e->getMessage(),
	            code: (int) $e->getCode(),
	            previous: $e
	        );
	    }
	}

	public function remove(User $user): void
	{
		try {
	        DB::transaction(
	            callback: fn () => $this->userQuery->remove(user: $user),
	            attempts: 3
	        );
	    }

	    catch (QueryException $e) {
	        Log::error(
	            message: 'Database Error: ' . $e->getMessage(),
	            context: [
	                'code' => $e->getCode(),
	                'bindings' => $e->getBindings(),
	                'sql' => $e->getSql()
	            ]
	        );

	        throw new \RuntimeException(
	            message: 'Error Deleting User: ' . $e->getMessage(),
	            code: (int) $e->getCode(),
	            previous: $e
	        );
	    }
	}
}

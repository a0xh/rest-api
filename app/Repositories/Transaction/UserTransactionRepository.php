<?php declare(strict_types=1);

namespace App\Repositories\Transaction;

use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Interface\Repository\Storage\UserStorageRepositoryInterface;
use Illuminate\Database\QueryException;
use App\Repositories\Doctrine\UserDoctrineRepository;
use App\Entities\User;

final class UserTransactionRepository implements UserStorageRepositoryInterface
{
	private UserDoctrineRepository $userDoctrine;

	public function __construct(UserDoctrineRepository $userDoctrine)
	{
		$this->userDoctrine = $userDoctrine;
	}

	public function save(User $user): void
	{
		try {
	        DB::transaction(
	            callback: fn () => $this->userDoctrine->save(user: $user),
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
	            callback: fn () => $this->userDoctrine->remove(user: $user),
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

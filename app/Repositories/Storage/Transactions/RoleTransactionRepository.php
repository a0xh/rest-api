<?php declare(strict_types=1);

namespace App\Repositories\Storage\Transactions;

use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Interface\Repositories\RoleRepositoryInterface;
use Illuminate\Database\QueryException;
use App\Repositories\Storage\Queries\RoleQueryRepository;
use App\Entities\Role;

final class RoleTransactionRepository implements RoleRepositoryInterface
{
	private RoleQueryRepository $roleQuery;

	public function __construct(RoleQueryRepository $roleQuery)
	{
		$this->roleQuery = $roleQuery;
	}

	public function save(Role $role): void
	{
		try {
	        DB::transaction(
	            callback: fn () => $this->roleQuery->save(role: $role),
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
	            message: 'Error Saving Role: ' . $e->getMessage(),
	            code: (int) $e->getCode(),
	            previous: $e
	        );
	    }
	}

	public function remove(Role $role): void
	{
		try {
	        DB::transaction(
	            callback: fn () => $this->roleQuery->remove(role: $role),
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
	            message: 'Error Deleting Role: ' . $e->getMessage(),
	            code: (int) $e->getCode(),
	            previous: $e
	        );
	    }
	}
}

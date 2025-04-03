<?php declare(strict_types=1);

namespace App\Repositories\Storage\Transactions;

use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Interface\Repositories\PermissionRepositoryInterface;
use Illuminate\Database\QueryException;
use App\Repositories\Storage\Queries\PermissionQueryRepository;
use App\Entities\Permission;

final class PermissionTransactionRepository implements PermissionRepositoryInterface
{
	private PermissionQueryRepository $permissionQuery;

	public function __construct(PermissionQueryRepository $permissionQuery)
	{
		$this->permissionQuery = $permissionQuery;
	}

	public function save(Permission $permission): void
	{
		try {
	        DB::transaction(
	            callback: fn () => $this->permissionQuery->save(permission: $permission),
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
	            message: 'Error Saving Permission: ' . $e->getMessage(),
	            code: (int) $e->getCode(),
	            previous: $e
	        );
	    }
	}

	public function remove(Permission $permission): void
	{
		try {
	        DB::transaction(
	            callback: fn () => $this->permissionQuery->remove(permission: $permission),
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
	            message: 'Error Deleting Permission: ' . $e->getMessage(),
	            code: (int) $e->getCode(),
	            previous: $e
	        );
	    }
	}
}

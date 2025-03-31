<?php declare(strict_types=1);

namespace App\Repositories\Transaction;

use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Interface\Repository\PermissionRepositoryInterface;
use Illuminate\Database\QueryException;
use App\Repositories\Doctrine\PermissionDoctrineRepository;
use App\Entities\Permission;

final class PermissionTransactionRepository implements PermissionRepositoryInterface
{
	private PermissionDoctrineRepository $permissionDoctrine;

	public function __construct(PermissionDoctrineRepository $permissionDoctrine)
	{
		$this->permissionDoctrine = $permissionDoctrine;
	}

	public function save(Permission $permission): void
	{
		try {
	        DB::transaction(
	            callback: fn () => $this->permissionDoctrine->save(permission: $permission),
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
	            callback: fn () => $this->permissionDoctrine->remove(permission: $permission),
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

<?php declare(strict_types=1);

namespace App\Repositories\Transaction;

use Illuminate\Support\Facades\{DB, Log};
use App\Contracts\Interface\Repository\Storage\MediaStorageRepositoryInterface;
use Illuminate\Database\QueryException;
use App\Repositories\Doctrine\MediaDoctrineRepository;
use App\Entities\Media;

final class MediaTransactionRepository implements MediaStorageRepositoryInterface
{
	private MediaDoctrineRepository $mediaDoctrine;

	public function __construct(MediaDoctrineRepository $mediaDoctrine)
	{
		$this->mediaDoctrine = $mediaDoctrine;
	}

	public function save(Media $media): void
	{
		try {
	        DB::transaction(
	            callback: fn () => $this->mediaDoctrine->save(media: $media),
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
	            message: 'Error Saving Media: ' . $e->getMessage(),
	            code: (int) $e->getCode(),
	            previous: $e
	        );
	    }
	}

	public function remove(Media $media): void
	{
		try {
	        DB::transaction(
	            callback: fn () => $this->mediaDoctrine->remove(media: $media),
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
	            message: 'Error Deleting Media: ' . $e->getMessage(),
	            code: (int) $e->getCode(),
	            previous: $e
	        );
	    }
	}
}

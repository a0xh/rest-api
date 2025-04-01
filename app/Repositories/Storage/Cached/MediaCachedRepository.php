<?php declare(strict_types=1);

namespace App\Repositories\Storage\Cached;

use App\Contracts\Abstract\MediaRepositoryAbstract;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Storage\Transactions\MediaTransactionRepository;
use Ramsey\Uuid\UuidInterface;
use App\Repositories\Storage\Queries\MediaQueryRepository;
use App\Entities\Media;
use Carbon\Carbon;

final class MediaCachedRepository extends MediaRepositoryAbstract
{
	private const CACHE_MEDIA_ALL_KEY = 'media';

	public function __construct(
		private MediaQueryRepository $mediaQuery,
        private MediaTransactionRepository $mediaTransaction
	) {}

	public function all(): array
    {
        return Cache::flexible(
        	key: self::CACHE_MEDIA_ALL_KEY,
        	ttl: [
                Carbon::now()->addMinutes(value: 5),
                Carbon::now()->addMinutes(value: 15)
            ],
        	callback: fn () => $this->mediaQuery->all(),
        	lock: ['seconds' => 10]
        );
    }

    public function findById(UuidInterface $id): ?Media
    {
        return $this->mediaQuery->findById(id: $id);
    }

    public function findByEntityId(string $entityId): array
    {
        return $this->mediaQuery->findByEntityId(entityId: $entityId);
    }

    public function save(Media $media): void
    {
        $this->mediaTransaction->save(media: $media);

    	if (Cache::has(key: self::CACHE_MEDIA_ALL_KEY)) {
    		Cache::forget(key: self::CACHE_MEDIA_ALL_KEY);
    	}
    }

    public function remove(Media $media): void
    {
    	$this->mediaTransaction->remove(media: $media);

    	if (Cache::has(key: self::CACHE_MEDIA_ALL_KEY)) {
    		Cache::forget(key: self::CACHE_MEDIA_ALL_KEY);
    	}
    }
}

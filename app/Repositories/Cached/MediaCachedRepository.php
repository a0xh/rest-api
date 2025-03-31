<?php declare(strict_types=1);

namespace App\Repositories\Cached;

use App\Contracts\Abstract\MediaRepositoryAbstract;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Transaction\MediaTransactionRepository;
use Ramsey\Uuid\UuidInterface;
use App\Repositories\Doctrine\MediaDoctrineRepository;
use App\Entities\Media;
use Carbon\Carbon;

final class MediaCachedRepository extends MediaRepositoryAbstract
{
	private const CACHE_MEDIA_ALL_KEY = 'media';

	public function __construct(
		private MediaDoctrineRepository $mediaDoctrine,
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
        	callback: fn () => $this->mediaDoctrine->all(),
        	lock: ['seconds' => 10]
        );
    }

    public function findById(UuidInterface $id): ?Media
    {
        return $this->mediaDoctrine->findById(id: $id);
    }

    public function findByEntityId(string $entityId): array
    {
        return $this->mediaDoctrine->findByEntityId(entityId: $entityId);
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

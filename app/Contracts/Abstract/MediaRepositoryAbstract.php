<?php declare(strict_types=1);

namespace App\Contracts\Abstract;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repository\MediaRepositoryInterface;
use App\Entities\Media;

abstract class MediaRepositoryAbstract implements MediaRepositoryInterface
{
    private MediaRepositoryInterface $mediaRepository;

    public function __construct(MediaRepositoryInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    abstract protected function all(): array;
    abstract protected function findById(UuidInterface $id): ?Media;
    abstract protected function findByEntityId(string $entityId): array;
}

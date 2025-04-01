<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories\Memory;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\Storage\MediaStorageRepositoryInterface;
use App\Entities\Media;

interface MediaMemoryRepositoryInterface extends MediaStorageRepositoryInterface
{
    public function all(): array;
    public function findById(UuidInterface $id): ?Media;
    public function findByEntityId(string $entityId): array;
}

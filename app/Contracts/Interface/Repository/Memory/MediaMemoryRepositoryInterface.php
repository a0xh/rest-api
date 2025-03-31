<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repository\Memory;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repository\Storage\MediaStorageRepositoryInterface;
use App\Entities\Media;

interface MediaMemoryRepositoryInterface extends MediaStorageRepositoryInterface
{
    public function all(): array;
    public function findById(UuidInterface $id): ?Media;
    public function findByEntityId(string $entityId): array;
}

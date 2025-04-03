<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories\Storage;

use Ramsey\Uuid\UuidInterface;
use App\Contracts\Interface\Repositories\MediaRepositoryInterface;
use App\Entities\Media;

interface MediaStorageRepositoryInterface extends MediaRepositoryInterface
{
    public function all(): array;
    public function findById(UuidInterface $id): ?Media;
    public function findByEntityId(string $entityId): array;
}

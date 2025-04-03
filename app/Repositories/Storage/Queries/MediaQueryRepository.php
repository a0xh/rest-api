<?php declare(strict_types=1);

namespace App\Repositories\Storage\Queries;

use App\Entities\Media;
use App\Contracts\Interface\Repositories\Storage\MediaStorageRepositoryInterface;
use Doctrine\ORM\{EntityManagerInterface, ORMException};
use Ramsey\Uuid\UuidInterface;

final class MediaQueryRepository implements MediaStorageRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function all(): array
    {
        return $this->entityManager->getRepository(
            className: Media::class
        )->findBy(
            criteria: [],
            orderBy: ['createdAt' => 'DESC']
        );
    }

    public function findById(UuidInterface $id): ?Media
    {
        return $this->entityManager->getRepository(
            className: Media::class
        )->find(
            id: $id
        );
    }

    public function findByEntityId(string $entityId): array
    {
        return $this->entityManager->getRepository(
            className: Media::class
        )->findBy(
            criteria: ['entityId' => $entityId],
            orderBy: ['createdAt' => 'DESC']
        );
    }

    public function save(Media $media): void
    {
        try {
            $this->entityManager->persist(object: $media);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed to save media: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }

    public function remove(Media $media): void
    {
        try {
            $this->entityManager->remove(object: $media);
            $this->entityManager->flush();
        }

        catch (ORMException $e) {
            throw new \RuntimeException(
                message: "Failed to delete media: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}

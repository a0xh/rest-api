<?php declare(strict_types=1);

namespace App\Repositories\Storage\Queries;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use App\Contracts\Abstract\MediaRepositoryAbstract;
use Ramsey\Uuid\UuidInterface;
use App\Entities\Media;

final class MediaQueryRepository extends MediaRepositoryAbstract
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
                message: "Failed To Save Media: {$e->getMessage()}",
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
                message: "Failed To Delete Media: {$e->getMessage()}",
                code: (int) $e->getCode(),
                previous: $e
            );
        }
    }
}

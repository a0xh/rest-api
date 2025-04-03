<?php declare(strict_types=1);

namespace App\Modules\Account\Handlers\Write;

use App\Shared\Handler;
use App\Contracts\Interface\Repositories\MediaRepositoryInterface;
use App\Modules\Account\Events\MediaWasUploaded;
use App\Entities\{Media, User};

final class UploadMediaHandler extends Handler
{
    public function __construct(
        private MediaRepositoryInterface $mediaRepository
    ) {}

    public function handle(MediaWasUploaded $event): void
    {
        $avatar = $event->avatar;

        $filePath = $avatar->store(
            path: 'avatars/' . date(format: 'Y-m-d')
        );

        $user = $event->user;

        $media = new Media(
            entityType: User::class,
            entityId: $user->getId()->toString(),
            filePath: $filePath
        );

        $media->setUser(user: $user);

        $this->mediaRepository->save(media: $media);
    }
}

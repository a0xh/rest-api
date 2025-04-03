<?php declare(strict_types=1);

namespace App\Modules\Account\Handlers\Write;

use App\Shared\Handler;
use App\Modules\Account\Events\MediaWasUpdated;
use App\Contracts\Interface\Repositories\MediaRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use App\Entities\{Media, User};

final class UpdateMediaHandler extends Handler
{
    public function __construct(
        private MediaRepositoryInterface $mediaRepository
    ) {}

    public function handle(MediaWasUpdated $event): void
    {
        $avatar = $event->avatar;
        $user = $event->user;

        // Получение текущих медиафайлов пользователя
        $currentMedia = $this->mediaRepository->findByEntityId($user->getId()->toString());

        foreach ($currentMedia as $media) {
            // Убедитесь, что сущность находится в управляемом состоянии
            $managedMedia = $this->mediaRepository->findById($media->getId());
            if ($managedMedia) {
                // Удаление файла с диска
                $filePath = $managedMedia->getFilePath();
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }

                // Удаление записи из базы данных
                $this->mediaRepository->remove($managedMedia);
            }
        }

        // Загрузка нового файла, если он предоставлен
        if ($avatar) {
            $filePath = $avatar->store(
                path: 'avatars/' . date(format: 'Y-m-d'),
                options: ['disk' => 'public']
            );

            // Создание новой записи медиафайла
            $media = new Media(
                entityType: User::class,
                entityId: $user->getId()->toString(),
                filePath: $filePath
            );

            $media->setUser(user: $user);

            // Сохранение новой записи в базе данных
            $this->mediaRepository->save(media: $media);
        }
    }
}

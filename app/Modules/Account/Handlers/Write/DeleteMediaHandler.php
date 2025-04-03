<?php declare(strict_types=1);

namespace App\Modules\Account\Handlers\Write;

use App\Shared\Handler;
use App\Modules\Account\Events\MediaWasDeleted;
use App\Contracts\Interface\Repositories\MediaRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use App\Entities\{Media, User};

final class DeleteMediaHandler extends Handler
{
    public function __construct(
        private MediaRepositoryInterface $mediaRepository
    ) {}

    public function handle(MediaWasDeleted $event): void
    {
        $userId = $event->userId->toString();

        // Получение текущих медиафайлов пользователя
        $currentMedia = $this->mediaRepository->findByEntityId($userId);

        foreach ($currentMedia as $media) {
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
    }
}

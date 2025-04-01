<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repositories\Storage;

use App\Contracts\Interface\Repositories\MediaRepositoryInterface;
use App\Entities\Media;

interface MediaStorageRepositoryInterface extends MediaRepositoryInterface
{
    public function save(Media $media): void;
    public function remove(Media $media): void;
}

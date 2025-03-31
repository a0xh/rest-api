<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repository\Storage;

use App\Entities\Media;

interface MediaStorageRepositoryInterface
{
    public function save(Media $media): void;
    public function remove(Media $media): void;
}

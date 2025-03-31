<?php declare(strict_types=1);

namespace App\Contracts\Interface\Repository;

use App\Entities\Media;

interface MediaRepositoryInterface
{
    public function save(Media $media): void;
    public function remove(Media $media): void;
}

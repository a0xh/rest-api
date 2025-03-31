<?php declare(strict_types=1);

namespace App\DtoCasts;

use WendellAdriel\ValidatedDTO\Casting\Castable;
use Illuminate\Http\UploadedFile;

final class MediaCast implements Castable
{
    public function cast(string $property, mixed $value): mixed
    {
        if ($value instanceof UploadedFile) {
            return $value;
        }

        return null;
    }
}

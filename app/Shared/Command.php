<?php declare(strict_types=1);

namespace App\Shared;

use WendellAdriel\ValidatedDTO\ValidatedDTO;
use WendellAdriel\ValidatedDTO\Concerns\EmptyCasts;

abstract class Command extends ValidatedDTO
{
    use EmptyCasts;

    protected function rules(): array
    {
        return [];
    }

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }
}

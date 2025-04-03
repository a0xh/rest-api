<?php declare(strict_types=1);

namespace App\Shared;

use WendellAdriel\ValidatedDTO\SimpleDTO;
use WendellAdriel\ValidatedDTO\Concerns\EmptyCasts;

abstract class Command extends SimpleDTO
{
    use EmptyCasts;

    public function rules(): array
    {
        return [];
    }

    public function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [];
    }
}

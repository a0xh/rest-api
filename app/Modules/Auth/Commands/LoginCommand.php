<?php declare(strict_types=1);

namespace App\Modules\Auth\Commands;

use App\Shared\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class LoginCommand extends Command
{
    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    #[Cast(type: StringCast::class, param: null)]
    public string $password;
}

<?php declare(strict_types=1);

namespace App\Modules\Auth\Commands;

use App\Shared\Command;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use App\DtoCasts\PasswordHashCast;

final class RegisterCommand extends Command
{
    #[Cast(type: StringCast::class, param: null)]
    public string $firstName;

    #[Cast(type: StringCast::class, param: null)]
    public string $lastName;

    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    #[Cast(type: PasswordHashCast::class, param: null)]
    public string $password;

    protected function mapData(): array
    {
        return [
            'first_name' => 'firstName',
            'last_name' => 'lastName',
        ];
    }
}

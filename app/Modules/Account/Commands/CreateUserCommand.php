<?php declare(strict_types=1);

namespace App\Modules\Account\Commands;

use App\Shared\Command;
use App\DtoCasts\PasswordHashCast;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use App\DtoCasts\MediaCast;
use WendellAdriel\ValidatedDTO\Casting\BooleanCast;
use Ramsey\Uuid\UuidInterface;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use Illuminate\Http\UploadedFile;
use App\DtoCasts\UuidCast;

final class CreateUserCommand extends Command
{
    #[Cast(type: MediaCast::class, param: null)]
    public UploadedFile $avatar;

    #[Cast(type: StringCast::class, param: null)]
    public string $firstName;

    #[Cast(type: StringCast::class, param: null)]
    public string $lastName;

    #[Cast(type: StringCast::class, param: null)]
    public string $patronymic;

    #[Cast(type: StringCast::class, param: null)]
    public string $phone;

    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    #[Cast(type: PasswordHashCast::class, param: null)]
    public string $password;

    #[Cast(type: BooleanCast::class, param: null)]
    public bool $status;

    #[Cast(type: UuidCast::class, param: null)]
    public UuidInterface $roleId;

    protected function mapData(): array
    {
        return [
            'first_name' => 'firstName',
            'last_name' => 'lastName',
            'role_id' => 'roleId',
        ];
    }
}

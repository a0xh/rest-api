<?php declare(strict_types=1);

namespace App\Modules\Account\Handlers\Write;

use App\Shared\Handler;
use App\Modules\Account\Commands\CreateUserCommand;
use App\Contracts\Interface\Repositories\UserRepositoryInterface;
use App\Contracts\Interface\Repositories\RoleRepositoryInterface;
use App\Modules\Account\Events\MediaWasUploaded;
use Illuminate\Support\Facades\Log;
use App\Entities\User;

final class CreateUserHandler extends Handler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository,
    ) {}

    public function handle(CreateUserCommand $command): bool
    {
        $user = new User(
            firstName: $command->firstName,
            lastName: $command->lastName,
            email: $command->email,
            password: $command->password,
        );

        $user->setPatronymic(patronymic: $command->patronymic);
        $user->setPhone(phone: $command->phone);
        $user->setStatus(status: $command->status);

        $role = $this->roleRepository->findById(
            id: $command->roleId
        );

        $user->setRole(role: $role);

        $this->userRepository->save(user: $user);

        event(new MediaWasUploaded(
            avatar: $command->avatar,
            user: $user
        ));

        return $user ? true : false;
    }
}
